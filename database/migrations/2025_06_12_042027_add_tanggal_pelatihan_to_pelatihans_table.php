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
        Schema::table('pelatihans', function (Blueprint $table) {
            // Menambah kolom 'tanggal_pelatihan' setelah kolom 'kode_pelatihan'
            // nullable() berarti kolom ini boleh kosong, penting untuk data lama.
            $table->date('tanggal_pelatihan')->nullable()->after('kode_pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelatihans', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('tanggal_pelatihan');
        });
    }
};