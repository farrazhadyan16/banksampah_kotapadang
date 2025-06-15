<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Riwayat;
use App\Models\Sampah;
use App\Models\Setoran;
use App\Models\TarikSaldo;

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
        $totalAdmin = User::where("role", "admin")->count();

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

        $chartSampahBar = $chartSampahPie; // sama saja datanya

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

        // Transaksi terbaru (limit 5)
        $recentRiwayat = Riwayat::with("nasabah")->latest()->take(5)->get();

        return view(
            "home",
            compact(
                "totalNasabah",
                "totalAdmin",
                "totalSetoran",
                "totalTarik",
                "sampahList",
                "chartSampahPie",
                "chartSampahBar",
                "chartTransaksi",
                "recentRiwayat"
            )
        );
    }
}
