<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathway_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phase_id')
                ->constrained('pathway_phases')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('task_order');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('category', [
                'language', 'academic', 'document',
                'experience', 'test', 'application', 'other'
            ])->default('other');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium')->index();
            $table->string('estimated_duration', 50)->nullable();
            $table->timestamps();

            $table->unique(['phase_id', 'task_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathway_tasks');
    }
};