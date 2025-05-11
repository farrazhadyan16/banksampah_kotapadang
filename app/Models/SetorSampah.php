<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetorSampah extends Model
{
    use HasFactory;

    protected $table = 'setoran';

    protected $fillable = ['id_nasabah', 'id_sampah', 'jumlah', 'total_harga', 'id_riwayat','status'];

    public function riwayat()
    {
        return $this->belongsTo(Riwayat::class, 'id_riwayat');
    }
    // In Setoran.php
    public function user()
    {
        return $this->belongsTo(User::class, 'id_nasabah');
    }


}