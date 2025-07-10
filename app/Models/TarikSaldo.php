<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TarikSaldo extends Model
{
    use HasFactory;

    protected $table = "tarik_saldo";

    protected $fillable = [
        "id_nasabah",
        "jumlah",
        "id_riwayat",
        "nama_bank",
        "rek_bank",
        "tujuan_bank",
    ];

    public function riwayat()
    {
        return $this->belongsTo(Riwayat::class, "id_riwayat");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "id_nasabah");
    }
}
