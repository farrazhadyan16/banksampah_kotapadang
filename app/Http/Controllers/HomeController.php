<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Riwayat;
use App\Models\Sampah;
use App\Models\Setoran;
use App\Models\TarikSaldo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $userRole = $user->role;

        // Ambil saldo user
        $saldoUser = $user->saldo ?? 0;

        // Total setoran user per jenis sampah per bulan
        $chartSetoranUser = [
            "labels" => [],
            "datasets" => [],
        ];

        if ($userRole === "nasabah") {
            $jenisSampah = Sampah::pluck("jenis_sampah", "id")->toArray();
            $warnaSampah = [
                "#36b9cc",
                "#f6c23e",
                "#e74a3b",
                "#4e73df",
                "#1cc88a",
                "#858796",
            ];
            $warnaIndex = 0;

            foreach ($jenisSampah as $idSampah => $namaSampah) {
                $dataPerBulan = [];

                for ($i = 5; $i >= 0; $i--) {
                    $bulan = now()->subMonths($i);
                    $label = $bulan->format("M Y");

                    if (!in_array($label, $chartSetoranUser["labels"])) {
                        $chartSetoranUser["labels"][] = $label;
                    }

                    $jumlah = DB::table("setoran_detail")
                        ->join(
                            "setoran",
                            "setoran_detail.id_setoran",
                            "=",
                            "setoran.id"
                        )
                        ->where("setoran.id_nasabah", $userId)
                        ->where("setoran_detail.id_sampah", $idSampah)
                        ->whereMonth("setoran_detail.created_at", $bulan->month)
                        ->whereYear("setoran_detail.created_at", $bulan->year)
                        ->sum("setoran_detail.jumlah_sampah");

                    $dataPerBulan[] = $jumlah;
                }

                $chartSetoranUser["datasets"][] = [
                    "label" => $namaSampah,
                    "data" => $dataPerBulan,
                    "borderColor" =>
                        $warnaSampah[$warnaIndex++ % count($warnaSampah)],
                    "fill" => false,
                ];
            }
        }

        // Data umum (admin/super_admin)
        $totalNasabah = User::where("role", "nasabah")->count();
        $totalSetoranProses = Setoran::where("status", "processing")->count();
        $totalSetoran = Riwayat::where("jenis_transaksi", "setoran")->count();
        $totalTarik = Riwayat::where("jenis_transaksi", "tarik_saldo")->count();

        $sampahList = Sampah::select("jenis_sampah", "jumlah")->get();

        $chartSampahPie = [
            "labels" => $sampahList->pluck("jenis_sampah"),
            "data" => $sampahList->pluck("jumlah"),
        ];

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

        $recentRiwayat = Riwayat::with("nasabah")->latest()->take(5)->get();

        $bulanLabels = [];
        $jenisSampahList = [
            "Plastik" => "botol_plastik",
            "Kaca" => "botol_kaca",
            "Kaleng" => "kaleng",
        ];
        $dataset = [];

        foreach ($jenisSampahList as $label => $keyword) {
            $dataset[$label] = [];
        }

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

        return view(
            "home",
            compact(
                "totalNasabah",
                "totalSetoranProses",
                "totalSetoran",
                "totalTarik",
                "sampahList",
                "chartSampahPie",
                "chartTransaksi",
                "recentRiwayat",
                "chartJenis",
                "saldoUser",
                "chartSetoranUser"
            )
        );
    }
}
