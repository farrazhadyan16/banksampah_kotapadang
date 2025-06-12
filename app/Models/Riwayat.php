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
    'id_riwayat',
    'no_rek',
    'jumlah',
    'jenis_transaksi',
    'user_id', // atau id_nasabah
    'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_nasabah');
    }
    public function nasabah()
    {
        return $this->belongsTo(User::class, 'id_nasabah');
    }
    public function tarikSaldo()
    {
        return $this->hasOne(TarikSaldo::class, 'id_riwayat');
    }

    public function setoran()
    {
        return $this->hasOne(Setoran::class, 'id_riwayat');
    }
}