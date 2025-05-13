<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIdSampahAndJumlahSampahFromSetoranTable extends Migration
{
    public function up()
    {
        Schema::table('setoran', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['id_sampah']);

        });
    }

    public function down()
    {

    }
}