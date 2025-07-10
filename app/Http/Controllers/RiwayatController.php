<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Riwayat;
use App\Models\User;
class RiwayatController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $query = Riwayat::with("nasabah");
        // Jika user adalah nasabah, tampilkan hanya riwayat miliknya
        if ($user->role === "nasabah") {
            $query->where("id_nasabah", $user->id);
        }
        // Filter berdasarkan tanggal
        if ($request->filled("start_date") && $request->filled("end_date")) {
            $query->whereBetween("created_at", [
                $request->start_date . " 00:00:00",
                $request->end_date . " 23:59:59",
            ]);
        }
        // Filter berdasarkan jenis transaksi
        if ($request->filled("jenis_transaksi")) {
            $query->where("jenis_transaksi", $request->jenis_transaksi);
        }
        // Exclude setoran yang status-nya Cancelled
        $query->where(function ($q) {
            $q->where("jenis_transaksi", "!=", "setoran")->orWhereHas(
                "setoran",
                function ($q2) {
                    $q2->where("status", "!=", "Cancelled");
                }
            );
        });

        $riwayat = $query->orderBy("created_at", "desc")->paginate(10);
        return view("riwayat", compact("riwayat"));
    }
}
