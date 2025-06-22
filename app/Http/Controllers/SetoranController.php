<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sampah;
use App\Models\Riwayat;
use App\Models\Setoran;
use App\Models\SetoranDetail;
class SetoranController extends Controller // {
{
    public function show()
    {
        // Ambil data harga sampah dari database (pastikan nama kolom sesuai)
        $hargaData = DB::table("sampah")->pluck("harga_satuan", "jenis_sampah");
        $hargaSampah = [
            "botol_plastik" => $hargaData["Botol Plastik"] ?? 0,
            "kaleng" => $hargaData["Kaleng"] ?? 0,
            "ban_karet" => $hargaData["Ban Karet"] ?? 0,
            "botol_kaca" => $hargaData["Botol Kaca"] ?? 0,
            "galon" => $hargaData["Galon"] ?? 0,
        ];
        return view("setoran", compact("hargaSampah"));
    }
    public function Setorankonfirmasi(Request $request)
    {
        $harga = DB::table("sampah")->pluck("harga_satuan", "jenis_sampah");
        $data = [
            "jumlah_botol_plastik" => (int) $request->input(
                "jumlah_botol_plastik",
                0
            ),
            "jumlah_kaleng" => (int) $request->input("jumlah_kaleng", 0),
            "jumlah_ban_karet" => (int) $request->input("jumlah_ban_karet", 0),
            "jumlah_botol_kaca" => (int) $request->input(
                "jumlah_botol_kaca",
                0
            ),
            "jumlah_galon" => (int) $request->input("jumlah_galon", 0),
            "harga_botol_plastik" => $harga["Botol Plastik"] ?? 0,
            "harga_kaleng" => $harga["Kaleng"] ?? 0,
            "harga_ban_karet" => $harga["Ban Karet"] ?? 0,
            "harga_botol_kaca" => $harga["Botol Kaca"] ?? 0,
            "harga_galon" => $harga["Galon"] ?? 0,
        ];
        $total = 0;
        foreach (
            ["botol_plastik", "kaleng", "ban_karet", "botol_kaca", "galon"]
            as $jenis
        ) {
            $jumlah = $data["jumlah_{$jenis}"];
            $hargaSatuan = $data["harga_{$jenis}"];
            $total += $jumlah * $hargaSatuan;
        }
        $data["total"] = $total;
        session(["data_setoran" => $data]);
        return view("konfirmasi", compact("data"));
    }
    public function konfirmasi(Request $request)
    {
        $userId = auth()->id();
        $data = session("data_setoran");
        if (!$data) {
            return redirect()
                ->route("setoran")
                ->with("error", "Data setoran tidak ditemukan.");
        }
        DB::beginTransaction();
        try {
            $riwayatId = DB::table("riwayat")->insertGetId([
                "id_nasabah" => $userId,
                "jenis_transaksi" => "Setoran",
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            $setoran = Setoran::create([
                "id_nasabah" => $userId,
                "id_riwayat" => $riwayatId,
                "total_harga" => $data["total"],
                "status" => "Processing",
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            $setoranId = $setoran->id;
            $sampahMap = DB::table("sampah")->pluck("id", "jenis_sampah");
            $jenisSampah = [
                "Botol Plastik" => $data["jumlah_botol_plastik"],
                "Kaleng" => $data["jumlah_kaleng"],
                "Ban Karet" => $data["jumlah_ban_karet"],
                "Botol Kaca" => $data["jumlah_botol_kaca"],
                "Galon" => $data["jumlah_galon"],
            ];
            foreach ($jenisSampah as $jenis => $jumlah) {
                if ($jumlah <= 0) {
                    continue;
                }
                $idSampah = $sampahMap[$jenis] ?? null;
                if (!$idSampah) {
                    continue;
                }
                $hargaSatuan = DB::table("sampah")
                    ->where("id", $idSampah)
                    ->value("harga_satuan");
                $totalHarga = $jumlah * $hargaSatuan;
                DB::table("sampah")
                    ->where("id", $idSampah)
                    ->increment("jumlah", $jumlah);
                SetoranDetail::create([
                    "id_setoran" => $setoranId,
                    "id_sampah" => $idSampah,
                    "jumlah_sampah" => $jumlah,
                    "harga_satuan" => $hargaSatuan,
                    "total_harga" => $totalHarga,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            DB::table("users")
                ->where("id", $userId)
                ->increment("saldo", $data["total"]);
            DB::commit();
            session()->forget("data_setoran");
            return redirect()->route("nota.show", $riwayatId);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with(
                "error",
                "Gagal menyimpan setoran: " . $e->getMessage()
            );
        }
    }
}
