<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Setoran;
use App\Models\Sampah;
use App\Models\User;
use App\Models\SetoranDetail;

class OrderListController extends Controller
{
    public function show(Request $request)
    {
        $query = Setoran::with("user", "details");

        // Filter
        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }
        if ($request->filled("tanggal")) {
            $query->whereDate("created_at", $request->tanggal);
        }

        // Sorting
        $sortBy = $request->input("sort_by", "id_riwayat");
        $sortDirection = $request->input("sort_direction", "desc");
        if (in_array($sortBy, ["id_riwayat", "id_nasabah", "created_at"])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $orders = $query->paginate(10)->appends($request->all());

        return view("orderlist", compact("orders"));
    }

    public function updateBerat(Request $request, $id)
    {
        $request->validate([
            "detail_id" => "required|array",
            "berat_sampah" => "required|array",
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->detail_id as $index => $detailId) {
                $berat = $request->berat_sampah[$index];

                $detail = SetoranDetail::find($detailId);
                if ($detail && $berat >= 0) {
                    $detail->berat_sampah = $berat;
                    $detail->total_harga = $berat * $detail->harga_kg;
                    $detail->verifikator = Auth::user()->name;
                    $detail->save();

                    // JANGAN update jumlah sampah di sini
                }
            }

            // Update total harga di setoran
            $setoran = Setoran::find($request->route("id"));
            if ($setoran) {
                $totalBaru = $setoran->details->sum("total_harga");
                $setoran->total_harga = $totalBaru;
                $setoran->updated_at = now();
                $setoran->save();
            }
        });

        return back()->with(
            "success",
            "Berat sampah dan total harga berhasil diperbarui."
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            "status" => "required|in:Completed,Processing,Rejected,Cancelled",
        ]);

        DB::transaction(function () use ($request, $id) {
            $setoran = Setoran::with(["user", "details.sampah"])->findOrFail(
                $id
            );
            $oldStatus = $setoran->status;
            $newStatus = $request->status;
            $user = $setoran->user;

            // Update status
            $setoran->status = $newStatus;
            $setoran->updated_at = now();
            $setoran->save();

            // Update juga di tabel riwayat
            $setoran->riwayat()->update([
                "updated_at" => now(),
            ]);

            // Jika status menjadi Completed dari bukan Completed
            if ($newStatus === "Completed" && $oldStatus !== "Completed") {
                foreach ($setoran->details as $detail) {
                    $sampah = $detail->sampah;
                    if ($sampah) {
                        $sampah->jumlah += $detail->berat_sampah; // Tambah dari berat_sampah
                        $sampah->save();
                    }
                }

                $user->saldo += $setoran->total_harga;
                $user->save();
            }

            // Jika status berubah dari Completed ke selain Completed
            elseif ($oldStatus === "Completed" && $newStatus !== "Completed") {
                foreach ($setoran->details as $detail) {
                    $sampah = $detail->sampah;
                    if ($sampah) {
                        $sampah->jumlah -= $detail->berat_sampah; // Kurangi dari berat_sampah
                        $sampah->save();
                    }
                }

                $user->saldo -= $setoran->total_harga;
                $user->save();
            }

            // Jika hanya berubah di antara Processing <=> Rejected, tidak ada perubahan ke sampah/saldo
        });

        return redirect()
            ->route("orderlist.show")
            ->with("success", "Status berhasil diperbarui.");
    }
}
