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
        DB::transaction(function () use ($request) {
            $user = auth()->user();
            $total = $request->input("total");

            // Simpan riwayat
            $riwayat = Riwayat::create([
                "id_nasabah" => $user->id,
                "jenis_transaksi" => "setoran",
            ]);

            // Simpan setoran dengan status default Processing
            $setoran = Setoran::create([
                "id_nasabah" => $user->id,
                "id_riwayat" => $riwayat->id,
                "total_harga" => $total,
                "status" => "Processing",
            ]);

            // Simpan detail setoran dari form
            $details = [];
            foreach (["botol_plastik", "kaleng", "botol_kaca"] as $jenis) {
                $jumlah = $request->input("jumlah_$jenis");
                $harga = $request->input("harga_$jenis");
                if ($jumlah > 0) {
                    $idSampah = Sampah::where(
                        "jenis_sampah",
                        "LIKE",
                        "%" . ucwords(str_replace("_", " ", $jenis)) . "%"
                    )->value("id");
                    $details[] = [
                        "id_setoran" => $setoran->id,
                        "id_sampah" => $idSampah,
                        "jumlah_sampah" => $jumlah,
                        "harga_satuan" => $harga,
                        "total_harga" => $jumlah * $harga,
                    ];
                }
            }
            SetoranDetail::insert($details);
        });

        return redirect()
            ->route("setoran")
            ->with("success", "Setoran berhasil dikonfirmasi.");
    }
}