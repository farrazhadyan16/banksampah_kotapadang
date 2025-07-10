<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use App\Models\Riwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BatalSetoranController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $setorans = Setoran::where("id_nasabah", $userId)
            ->where("status", "Processing")
            ->latest()
            ->paginate(10);

        return view("batal", compact("setorans"));
    }

    public function batalkan($id)
    {
        DB::transaction(function () use ($id) {
            $setoran = Setoran::with("riwayat")
                ->where("id", $id)
                ->where("status", "Processing")
                ->firstOrFail();

            $setoran->status = "Cancelled";
            $setoran->save();

            $setoran->riwayat()->update([
                "updated_at" => now(),
            ]);
        });

        return redirect()
            ->route("setoran.batal")
            ->with("success", "Setoran berhasil dibatalkan.");
    }
}
