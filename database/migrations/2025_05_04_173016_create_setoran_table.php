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
        Schema::create('setoran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nasabah');
            $table->unsignedBigInteger('id_sampah');
            $table->decimal('jumlah_sampah', 10, 2);
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['pending', 'success'])->default('pending');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_nasabah')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_sampah')->references('id')->on('sampah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran');
    }
};