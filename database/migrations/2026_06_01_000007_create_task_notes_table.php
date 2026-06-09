<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
                ->constrained('pathway_tasks')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE task_notes ADD CONSTRAINT task_notes_content_length_check CHECK (CHAR_LENGTH(content) BETWEEN 1 AND 2000)');
    }

    public function down(): void
    {
        Schema::dropIfExists('task_notes');
    }
};