<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('target_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('title', 200);
            $table->text('summary')->nullable();
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->unsignedTinyInteger('generation_count')->default(1);
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathways');
    }
};