<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('setoran', function (Blueprint $table) {
            $table->dropColumn(['id_sampah', 'jumlah_sampah']);
        });
    }

    public function down()
    {
        Schema::table('setoran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sampah');
            $table->integer('jumlah_sampah');
        });
    }
};