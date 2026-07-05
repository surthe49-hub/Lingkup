<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_destinations', function (Blueprint $table) {
            $table->id();
            $table->string('flag_emoji', 10);
            $table->string('name', 100);
            $table->string('scholarship_name', 150);
            $table->string('image_path', 255);
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_destinations');
    }
};
