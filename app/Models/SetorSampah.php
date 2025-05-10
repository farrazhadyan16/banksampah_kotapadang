<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetorSampah extends Model
{
    use HasFactory;

    protected $table = 'setoran';

    protected $fillable = [
        'id',
        'id_riwayat',
        'jumlah_rp',
        // tambahkan kolom lain jika ada
    ];

    public function riwayat()
    {
        return $this->belongsTo(Riwayat::class, 'id_riwayat');
    }
}