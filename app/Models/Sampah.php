<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sampah extends Model
{
    use HasFactory;

    protected $table = "sampah"; // ← beri tahu nama tabel sebenarnya

    protected $fillable = ["jenis_sampah", "harga_kg", "jumlah"];
}
