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
            "nama_bank" => "required|string|max:100",
            "rek_bank" => "required|digits_between:6,20",
            "tujuan_bank" => "required|string|max:100",
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
        $user->save();

        $riwayat = Riwayat::create([
            "id_nasabah" => $user->id,
            "jenis_transaksi" => "tarik_saldo",
        ]);

        $tarik = TarikSaldo::create([
            "id_nasabah" => $user->id,
            "jumlah" => $jumlah,
            "id_riwayat" => $riwayat->id,
            "nama_bank" => $request->nama_bank,
            "rek_bank" => $request->rek_bank,
            "tujuan_bank" => $request->tujuan_bank,
        ]);

        return redirect()->route("nota.show", $riwayat->id);
    }
}
