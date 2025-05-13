<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
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
    
}