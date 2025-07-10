<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranDetail extends Model
{
    protected $table = "setoran_detail";

    protected $fillable = [
        "id_setoran",
        "id_sampah",
        "jumlah_sampah",
        "harga_kg",
        "total_harga",
    ];

    public $timestamps = false;

    public function sampah()
    {
        return $this->belongsTo(Sampah::class, "id_sampah");
    }
}
