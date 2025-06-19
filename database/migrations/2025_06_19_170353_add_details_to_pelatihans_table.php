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
            $table->text('deskripsi')->nullable()->after('nama_pelatihan');
            $table->string('lokasi')->nullable()->after('deskripsi');
            $table->string('nama_instruktur')->nullable()->after('lokasi');
            $table->unsignedInteger('kuota')->default(0)->after('nama_instruktur');
            $table->string('status')->default('Segera Hadir')->after('kuota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelatihans', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'lokasi', 'nama_instruktur', 'kuota', 'status']);
        });
    }
};
