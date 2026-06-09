<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pathway_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->index();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['pathway_id', 'user_id']);
        });

        DB::statement('ALTER TABLE feedback ADD CONSTRAINT feedback_rating_check CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};