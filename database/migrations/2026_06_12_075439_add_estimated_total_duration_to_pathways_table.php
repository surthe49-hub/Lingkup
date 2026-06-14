<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom estimated_total_duration ke tabel pathways.
     *
     * Kolom ini menyimpan estimasi total durasi roadmap dari output AI
     * (contoh: "12 bulan", "18 bulan"). Ditambahkan di Sprint 4 Phase 3A
     * karena output AI sudah menghasilkan field ini sejak Phase 2.
     */
    public function up(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->string('estimated_total_duration', 50)
                ->nullable()
                ->after('summary')
                ->comment('Estimasi total durasi pathway dari AI, contoh: "12 bulan"');
        });
    }

    public function down(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->dropColumn('estimated_total_duration');
        });
    }
};