<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Riwayat extends Model
{
    use HasFactory;

    protected $table = 'riwayat';

    protected $fillable = [
        'id_nasabah',
        'jenis_transaksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_nasabah');
    }
}