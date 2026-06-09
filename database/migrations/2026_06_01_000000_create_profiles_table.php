<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('major', 100);
            $table->enum('education_level', ['D3', 'S1', 'S2', 'S3'])->default('S1');
            $table->unsignedTinyInteger('semester');
            $table->decimal('gpa', 3, 2);
            $table->enum('english_level', ['beginner', 'intermediate', 'advanced', 'native'])
                ->default('intermediate');
            $table->unsignedSmallInteger('english_test_score')->nullable();
            $table->enum('english_test_type', ['TOEFL_ITP', 'TOEFL_IBT', 'IELTS', 'DUOLINGO'])
                ->nullable();
            $table->json('other_languages')->nullable();
            $table->text('organization_experience')->nullable();
            $table->json('interests')->nullable();
            $table->timestamps();
        });

        // Tambah CHECK constraints (MySQL 8.0.16+)
        DB::statement('ALTER TABLE profiles ADD CONSTRAINT profiles_semester_check CHECK (semester BETWEEN 1 AND 14)');
        DB::statement('ALTER TABLE profiles ADD CONSTRAINT profiles_gpa_check CHECK (gpa BETWEEN 0.00 AND 4.00)');
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};