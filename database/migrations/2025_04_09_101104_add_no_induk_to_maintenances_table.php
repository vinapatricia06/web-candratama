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
        Schema::table('maintenances', function (Blueprint $table) {
            // Menambahkan kolom 'no_induk' ke dalam tabel 'maintenances'
            $table->string('no_induk')->nullable(); // Sesuaikan tipe data dan nullable sesuai kebutuhan Anda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            // Menghapus kolom 'no_induk' jika rollback
            $table->dropColumn('no_induk');
        });
    }
};
