<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('role', 150);
            $table->enum('avatar_color', ['primary', 'peach', 'teal', 'green', 'pink'])
                ->default('primary');
            $table->unsignedTinyInteger('rating')->index();
            $table->text('message');
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE testimonials ADD CONSTRAINT testimonials_rating_check CHECK (rating BETWEEN 1 AND 5)');
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
