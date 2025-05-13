<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setoran;

class SetoranController extends Controller
{
    public function konfirmasiSetor(Request $request)
    {
        $harga = DB::table('sampah')->pluck('harga_satuan', 'jenis_sampah');

        $data = [
            'jumlah_botol_plastik' => (int) $request->input('jumlah_botol_plastik', 0),
            'jumlah_kaleng'        => (int) $request->input('jumlah_kaleng', 0),
            'jumlah_ban_karet'     => (int) $request->input('jumlah_ban_karet', 0),
            'jumlah_botol_kaca'    => (int) $request->input('jumlah_botol_kaca', 0),
            'jumlah_galon'         => (int) $request->input('jumlah_galon', 0),

            'harga_botol_plastik'  => $harga['Botol Plastik'] ?? 0,
            'harga_kaleng'         => $harga['Kaleng'] ?? 0,
            'harga_ban_karet'      => $harga['Ban Karet'] ?? 0,
            'harga_botol_kaca'     => $harga['Botol Kaca'] ?? 0,
            'harga_galon'          => $harga['Galon'] ?? 0,
        ];

        $total = 0;
        foreach (['botol_plastik', 'kaleng', 'ban_karet', 'botol_kaca', 'galon'] as $jenis) {
            $jumlah = $data["jumlah_{$jenis}"];
            $hargaSatuan = $data["harga_{$jenis}"];
            $total += $jumlah * $hargaSatuan;
        }

        $data['total'] = $total;

        session(['data_setoran' => $data]);

        return view('konfirmasi', compact('data'));
    }

    public function konfirmasi(Request $request)
{
    $userId = auth()->id();
    $data = session('data_setoran');

    if (!$data) {
        return redirect()->route('setoran')->with('error', 'Data setoran tidak ditemukan.');
    }

    DB::beginTransaction();

    try {
        // 1. Simpan ke tabel riwayat
        $riwayatId = DB::table('riwayat')->insertGetId([
            'id_nasabah' => $userId,
            'jenis_transaksi' => 'Setoran',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Ambil mapping id_sampah berdasarkan jenis_sampah
        $sampahMap = DB::table('sampah')->pluck('id', 'jenis_sampah');

        $jenisSampah = [
            'Botol Plastik' => $data['jumlah_botol_plastik'],
            'Kaleng'        => $data['jumlah_kaleng'],
            'Ban Karet'     => $data['jumlah_ban_karet'],
            'Botol Kaca'    => $data['jumlah_botol_kaca'],
            'Galon'         => $data['jumlah_galon'],
        ];

        // 3. Loop dan simpan ke setoran + update sampah
        foreach ($jenisSampah as $jenis => $jumlah) {
            if ($jumlah <= 0) continue;

            $idSampah = $sampahMap[$jenis] ?? null;
            if (!$idSampah) continue;

            $hargaSatuan = DB::table('sampah')->where('id', $idSampah)->value('harga_satuan');
            $totalHarga = $jumlah * $hargaSatuan;

            // Tambahkan jumlah di tabel sampah
            DB::table('sampah')->where('id', $idSampah)->increment('jumlah', $jumlah);

            // Simpan ke setoran
            DB::table('setoran')->insert([
                'id_nasabah' => $userId,
                'id_sampah' => $idSampah,
                'id_riwayat' => $riwayatId,
                'jumlah_sampah' => $jumlah,
                'total_harga' => $totalHarga,
                'status' => 'Processing',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Tambahkan saldo user
        DB::table('users')->where('id', $userId)->increment('saldo', $data['total']);

        DB::commit();
        session()->forget('data_setoran');

        return redirect()->route('setoran')->with('success', 'Setoran berhasil dikonfirmasi!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->route('setoran')->with('error', 'Gagal menyimpan setoran: ' . $e->getMessage());
    }
}

    public function simpan(Request $request)
    {
        $request->validate([
            'id_sampah' => 'required|exists:sampah,id',
            'total'     => 'required|numeric|min:0',
        ]);

        Setoran::create([
            'id_nasabah'       => auth()->id(),
            'id_sampah'        => $request->input('id_sampah'),
            'jenis_transaksi'  => 'setor_sampah',
            'total'            => $request->input('total'),
            'status'           => 'Processing',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()->route('setoran')->with('success', 'Setoran berhasil disimpan!');
    }

    private function generateRiwayatId()
    {
        return rand(8000000, 8999999);
    }
}