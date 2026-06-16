<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah indexes untuk performance query yang akan sering dipanggil
     * setelah Sprint 5: archive lookup dan rate limiting check.
     */
    public function up(): void
    {
        // Index untuk query: WHERE user_id = X AND status = 'active'
        // Dipakai oleh User::pathway() relationship dan dashboard
        Schema::table('pathways', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'pathways_user_status_idx');
        });

        // Index untuk rate limit query:
        // WHERE user_id = X AND target_id = Y AND status = 'success' AND created_at > Z
        Schema::table('pathway_generation_logs', function (Blueprint $table) {
            $table->index(
                ['user_id', 'target_id', 'status', 'created_at'],
                'logs_user_target_status_time_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->dropIndex('pathways_user_status_idx');
        });

        Schema::table('pathway_generation_logs', function (Blueprint $table) {
            $table->dropIndex('logs_user_target_status_time_idx');
        });
    }
};