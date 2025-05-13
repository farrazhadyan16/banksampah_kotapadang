<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setoran_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_setoran');
            $table->unsignedBigInteger('id_sampah');
            $table->integer('jumlah_sampah');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->timestamps();

            $table->foreign('id_setoran')->references('id')->on('setoran')->onDelete('cascade');
            $table->foreign('id_sampah')->references('id')->on('sampah')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_detail');    
    }
};