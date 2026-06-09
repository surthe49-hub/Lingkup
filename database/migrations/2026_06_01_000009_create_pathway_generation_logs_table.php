<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathway_generation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('pathway_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('target_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('model_used', 50);
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->decimal('cost_idr', 10, 2)->default(0.00);
            $table->unsignedInteger('latency_ms')->default(0);
            $table->enum('status', ['success', 'timeout', 'api_error', 'validation_failed']);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathway_generation_logs');
    }
};