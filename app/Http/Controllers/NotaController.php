<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Riwayat;
class NotaController extends Controller
{
    public function show($id, Request $request)
    {
        $riwayat = Riwayat::with([
            "nasabah",
            "setoran.setoranDetail.sampah",
            "tarikSaldo",
        ])->findOrFail($id);
        $from = $request->query("from", $riwayat->jenis_transaksi); // default ke jenis_transaksi jika tidak ada
        return view("nota", compact("riwayat", "from"));
    }
}
