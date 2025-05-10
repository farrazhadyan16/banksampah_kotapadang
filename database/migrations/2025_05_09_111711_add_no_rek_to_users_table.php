<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('no_rek')->unique()->nullable()->after('id');
        });

        // Isi no_rek otomatis untuk user yang sudah ada (dimulai dari 1000000)
        $users = DB::table('users')->orderBy('id')->get();
        $no = 1000000;

        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['no_rek' => $no++]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_rek');
        });
    }
};