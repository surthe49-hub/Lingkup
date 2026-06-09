<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('target_country', 100)->nullable()->after('english_test_type');

            $table->string('career_goal', 100)->nullable()->after('target_country');

            $table->json('current_skills')->nullable()->after('other_languages');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'target_country',
                'career_goal',
                'current_skills',
            ]);
        });
    }
};