<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Riwayat;

class NotaController extends Controller
{
    public function show($id)
    {
    $riwayat = Riwayat::with(['nasabah', 'tarikSaldo', 'setorSampah.setoranDetail.sampah'])->findOrFail($id);

        return view('nota', compact('riwayat'));
    }
}