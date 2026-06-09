<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'country',
        'education_level',
        'program_type',
        'requirements_summary',
        'structured_requirements',
        'typical_deadline',
        'official_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'structured_requirements' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('education_level', $level);
    }

    // ============================================
    // Relationships
    // ============================================

    public function userTargets(): HasMany
    {
        return $this->hasMany(UserTarget::class);
    }

    public function selectedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_targets')
            ->withPivot('selected_at')
            ->withTimestamps();
    }

    public function pathways(): HasMany
    {
        return $this->hasMany(Pathway::class);
    }

    public function generationLogs(): HasMany
    {
        return $this->hasMany(PathwayGenerationLog::class);
    }
}