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
        'other_languages',
        'organization_experience',
        'interests',
    ];

    protected function casts(): array
    {
        return [
            'semester' => 'integer',
            'gpa' => 'decimal:2',
            'english_test_score' => 'integer',
            'other_languages' => 'array',
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
}