<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Riwayat;
use App\Models\Sampah;
use App\Models\Setoran;
use App\Models\TarikSaldo;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        // Total pengguna berdasarkan role
        $totalNasabah = User::where("role", "nasabah")->count();
        $totalSetoranProses = Setoran::where("status", "processing")->count();

        // Total setoran dan tarik saldo (jumlah transaksi)
        $totalSetoran = Riwayat::where("jenis_transaksi", "setoran")->count();
        $totalTarik = Riwayat::where("jenis_transaksi", "tarik_saldo")->count();

        // Daftar sampah dan stoknya
        $sampahList = Sampah::select("jenis_sampah", "jumlah")->get();

        // Data chart - distribusi sampah (pie) dan stok (bar)
        $chartSampahPie = [
            "labels" => $sampahList->pluck("jenis_sampah"),
            "data" => $sampahList->pluck("jumlah"),
        ];

        $chartSampahBar = $chartSampahPie;

        // Transaksi bulanan
        $chartTransaksi = [
            "labels" => [],
            "setoran" => [],
            "tarik" => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $label = $bulan->format("M Y");
            $chartTransaksi["labels"][] = $label;

            $chartTransaksi["setoran"][] = Setoran::whereMonth(
                "created_at",
                $bulan->month
            )
                ->whereYear("created_at", $bulan->year)
                ->count();

            $chartTransaksi["tarik"][] = TarikSaldo::whereMonth(
                "created_at",
                $bulan->month
            )
                ->whereYear("created_at", $bulan->year)
                ->count();
        }

        // Transaksi terbaru
        $recentRiwayat = Riwayat::with("nasabah")->latest()->take(5)->get();

        // Grafik Bulanan per Jenis Sampah (Contoh: Plastik dan Kaca)
        $bulanLabels = [];
        $jenisSampahList = [
            "Plastik" => "botol_plastik",
            "Kaca" => "botol_kaca",
            "Kaleng" => "kaleng",
        ];
        $dataset = [];

        // Inisialisasi data kosong untuk tiap jenis
        foreach ($jenisSampahList as $label => $keyword) {
            $dataset[$label] = [];
        }

        // Loop per bulan
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $bulanLabels[] = $bulan->translatedFormat("F Y");

            foreach ($jenisSampahList as $label => $keyword) {
                $jumlah = DB::table("setoran_detail")
                    ->join(
                        "sampah",
                        "setoran_detail.id_sampah",
                        "=",
                        "sampah.id"
                    )
                    ->where("sampah.jenis_sampah", "like", "%$keyword%")
                    ->whereMonth("setoran_detail.created_at", $bulan->month)
                    ->whereYear("setoran_detail.created_at", $bulan->year)
                    ->sum("setoran_detail.jumlah_sampah");

                $dataset[$label][] = $jumlah;
            }
        }

        $chartJenis = [
            "labels" => $bulanLabels,
            "datasets" => [],
        ];

        // Warna berbeda untuk tiap jenis
        $warna = [
            "Plastik" => "#36b9cc",
            "Kaca" => "#f6c23e",
            "Kaleng" => "#e74a3b",
        ];

        foreach ($dataset as $label => $data) {
            $chartJenis["datasets"][] = [
                "label" => $label,
                "data" => $data,
                "borderColor" => $warna[$label] ?? "#000",
                "fill" => false,
            ];
        }

        // Return view (hanya 1x)
        return view(
            "home",
            compact(
                "totalNasabah",
                "totalSetoranProses",
                "totalSetoran",
                "totalTarik",
                "sampahList",
                "chartSampahPie",
                "chartSampahBar",
                "chartTransaksi",
                "recentRiwayat",
                "chartJenis"
            )
        );
    }
}
