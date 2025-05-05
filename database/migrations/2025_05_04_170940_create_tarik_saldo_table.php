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
        Schema::create('tarik_saldo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nasabah');
            $table->integer('jumlah');
            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('id_nasabah')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarik_saldo');
    }
};