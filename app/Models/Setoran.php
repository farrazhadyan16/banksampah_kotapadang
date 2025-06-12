<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;

    protected $table = 'setoran';

    protected $fillable = [
        'id_nasabah',
        'id_sampah',
        'id_riwayat',
        'jumlah_sampah',
        'total_harga',
        'status',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false; // Karena kamu pakai manual created_at dan updated_at

    public function detail()
    {
        return $this->hasMany(SetoranDetail::class, 'id_setoran');
    }

    public function setoranDetail()
    {
        return $this->hasMany(SetoranDetail::class, 'id_setoran', 'id');
    }

    public function riwayat()
    {
        return $this->belongsTo(Riwayat::class, 'id_riwayat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_nasabah');
    }
}