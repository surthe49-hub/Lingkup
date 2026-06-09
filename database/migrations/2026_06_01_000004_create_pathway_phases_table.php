<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathway_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pathway_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('phase_order');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('estimated_duration', 50)->nullable();
            $table->timestamps();

            $table->unique(['pathway_id', 'phase_order']);
        });

        DB::statement('ALTER TABLE pathway_phases ADD CONSTRAINT pathway_phases_phase_order_check CHECK (phase_order BETWEEN 1 AND 6)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pathway_phases');
    }
};