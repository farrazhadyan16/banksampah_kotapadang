<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TarikSaldo;
use App\Models\Riwayat;
use App\Models\User;

class TarikSaldoController extends Controller
{
    public function show()
    {
        return view("tarik_saldo");
    }

    public function store(Request $request)
    {
        $request->validate([
            "jumlah" => "required|numeric|min:1000",
        ]);

        $user = Auth::user();
        $jumlah = $request->jumlah;

        if ($user->saldo - $jumlah < 1000) {
            return back()->with(
                "error",
                "Saldo tidak cukup. Minimal sisa saldo Rp 1.000 setelah penarikan."
            );
        }

        $user->saldo -= $jumlah;
        // dd(get_class($user));
        $user->save();

        // 1. Catat ke tabel riwayat
        $riwayat = Riwayat::create([
            "id_nasabah" => $user->id,
            "jenis_transaksi" => "tarik_saldo",
        ]);

        // 2. Catat ke tabel tarik_saldo dan hubungkan ke riwayat
        $tarik = TarikSaldo::create([
            "id_nasabah" => $user->id,
            "jumlah" => $jumlah,
            "id_riwayat" => $riwayat->id,
        ]);

        // 3. Arahkan ke halaman nota
        return redirect()->route("nota.show", $riwayat->id);
    }

    // public function nota($id)
    // {
    //     $tarik = TarikSaldo::with('user')->findOrFail($id);

    //     return view('nota', compact('tarik'));
    // }
}
