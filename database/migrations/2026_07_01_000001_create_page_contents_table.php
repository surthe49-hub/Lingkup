<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('page', 30)->index();
            $table->string('section_key', 100);
            $table->longText('content')->nullable();
            $table->enum('content_type', ['text', 'richtext'])->default('text');
            $table->timestamps();

            $table->unique(['page', 'section_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
