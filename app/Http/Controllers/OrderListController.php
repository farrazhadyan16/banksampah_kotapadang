<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Setoran;
use App\Models\Sampah;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class OrderListController extends Controller
{
    public function show(Request $request)
    {
        $query = Setoran::with("user");
        // Filter by status
        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }
        // Filter by tanggal
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
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            "status" => "required|in:Completed,Processing,Rejected",
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

            // Update juga tanggal di riwayat
            $setoran->riwayat()->update([
                "updated_at" => now(), // Tambahan
            ]);

            // Jika status berubah ke Completed dan sebelumnya bukan Completed
            if ($newStatus === "Completed" && $oldStatus !== "Completed") {
                foreach ($setoran->details as $detail) {
                    $sampah = $detail->sampah;
                    $sampah->jumlah += $detail->jumlah_sampah;
                    $sampah->save();
                }

                $user->saldo += $setoran->total_harga;
                $user->save();
            }

            // Jika status berubah dari Completed ke selain Completed
            elseif ($oldStatus === "Completed" && $newStatus !== "Completed") {
                foreach ($setoran->details as $detail) {
                    $sampah = $detail->sampah;
                    $sampah->jumlah -= $detail->jumlah_sampah;
                    $sampah->save();
                }

                $user->saldo -= $setoran->total_harga;
                $user->save();
            }

            // Jika hanya dari Processing ⇄ Rejected, tidak ada perubahan
        });

        return redirect()
            ->route("orderlist.show")
            ->with("success", "Status berhasil diperbarui.");
    }
}
