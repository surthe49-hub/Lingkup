<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'major',
    'education_level',
    'semester',
    'gpa',
    'english_level',
    'english_test_score',
    'english_test_type',

    'target_country',
    'career_goal',

    'other_languages',
    'current_skills',
    'interests',
    'organization_experience',
    
];

   protected function casts(): array
{
    return [
        'semester' => 'integer',
        'gpa' => 'decimal:2',
        'english_test_score' => 'integer',

        'other_languages' => 'array',
        'current_skills' => 'array',
        'interests' => 'array',
    ];
}

    // ============================================
    // Relationships
    // ============================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ============================================
// Helper Methods
// ============================================

public function isComplete(): bool
{
    return !empty($this->major)
        && !empty($this->education_level)
        && !empty($this->semester)
        && !is_null($this->gpa)
        && !empty($this->english_level);
}

public function getCompletionPercentageAttribute(): int
{
    $allFields = [
        'major',
        'education_level',
        'semester',
        'gpa',
        'english_level',
        'english_test_type',
        'english_test_score',
        'target_country',
        'career_goal',
        'other_languages',
        'current_skills',
        'organization_experience',
        'interests',
    ];

    $filledCount = 0;

    foreach ($allFields as $field) {
        $value = $this->{$field};

        if (!is_null($value) && $value !== '' && $value !== []) {
            $filledCount++;
        }
    }

    return (int) round(
        ($filledCount / count($allFields)) * 100
    );
}
}

