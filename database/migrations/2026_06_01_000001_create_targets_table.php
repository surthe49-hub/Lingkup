<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('country', 50)->index();
            $table->enum('education_level', ['S1', 'S2', 'S3', 'Exchange', 'Internship']);
            $table->enum('program_type', ['scholarship', 'exchange', 'internship', 'dual_degree'])
                ->default('scholarship');
            $table->text('requirements_summary');
            $table->json('structured_requirements')->nullable();
            $table->string('typical_deadline', 50)->nullable();
            $table->string('official_url', 500);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};