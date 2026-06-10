<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Memperbesar kolom typical_deadline dari VARCHAR(50) ke VARCHAR(100).
     * Alasan: data seeder Sprint 3 mengandung deskripsi deadline yang lebih panjang
     * (contoh: "Februari setiap tahun (untuk intake tahun berikutnya)" = 53 char).
     */
    public function up(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->string('typical_deadline', 100)->nullable()->change();
        });
    }

    /**
     * Rollback ke VARCHAR(50).
     * WARNING: jika ada data > 50 char saat rollback, akan error.
     * Pastikan data dibersihkan dulu sebelum rollback.
     */
    public function down(): void
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->string('typical_deadline', 50)->nullable()->change();
        });
    }
};