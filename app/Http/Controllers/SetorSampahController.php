<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sampah;
use App\Models\Riwayat;
use App\Models\SetorSampah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SetorSampahController extends Controller
{
   public function create()
{
    $listnasabah = User::where('role', 'nasabah')->select('id', 'name')->get();
    $listsampah = Sampah::all(); // atau sesuai nama model kamu

    return view('setorsampah', compact('listnasabah', 'listsampah'));
}
    public function store(Request $request)
{
    $request->validate([
        'id_sampah' => 'required|exists:sampah,id',
        'jumlah_sampah' => 'required|numeric|min:0.01',
        'id_nasabah' => 'nullable|exists:users,id', // hanya diisi oleh admin/superadmin
        'total_harga' => 'required|numeric|min:1',

    ]);

    $user = Auth::user();
    $sampahId = $request->id_sampah;
    $jumlah_sampah = $request->jumlah_sampah;

    // Tentukan ID nasabah
    $nasabahId = $user->role == 'nasabah' ? $user->id : $request->id_nasabah;

    if (!$nasabahId) {
        return back()->withErrors(['id_nasabah' => 'Pilih nasabah terlebih dahulu.']);
    }

    // Ambil data dari request
$jumlah_sampah = $request->input('jumlah_sampah');

    // Ambil data sampah
    $sampah = Sampah::findOrFail($sampahId);
    $totalHarga = $jumlah_sampah * $sampah->harga_satuan;

    // Simpan ke tabel riwayat
    $riwayat = Riwayat::create([
        'jenis_transaksi' => 'setoran',
        'id_nasabah' => $nasabahId,
    ]);

    // Simpan ke tabel setoran
    SetorSampah::create([
        'id_nasabah' => $nasabahId,
        'id_sampah' => $sampahId,
        'jumlah_sampah' => $jumlah_sampah,
        'total_harga' => $totalHarga,
        'id_riwayat' => $riwayat->id,
    ]);

    // Update jumlah di tabel sampah
    $sampah->increment('jumlah', $jumlah_sampah);

    return redirect()->route('nota.show', ['id' => $riwayat->id]);
}


}