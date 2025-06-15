<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sampah;
use App\Models\Riwayat;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query("query");

        $nasabah = User::where("role", "nasabah")
            ->where(function ($q) use ($keyword) {
                $q->where("name", "like", "%$keyword%")->orWhere(
                    "email",
                    "like",
                    "%$keyword%"
                );
            })
            ->get();

        $sampah = Sampah::where("jenis_sampah", "like", "%$keyword%")->get();

        $riwayat = Riwayat::with("nasabah")
            ->where("jenis_transaksi", "like", "%$keyword%")
            ->orWhereHas("nasabah", function ($q) use ($keyword) {
                $q->where("name", "like", "%$keyword%");
            })
            ->get();

        return view(
            "result",
            compact("nasabah", "sampah", "riwayat", "keyword")
        );
    }
}
