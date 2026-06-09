<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PathwayTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'task_order',
        'title',
        'description',
        'category',
        'priority',
        'estimated_duration',
    ];

    protected function casts(): array
    {
        return [
            'task_order' => 'integer',
        ];
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ============================================
    // Relationships
    // ============================================

    public function phase(): BelongsTo
    {
        return $this->belongsTo(PathwayPhase::class, 'phase_id');
    }

    public function progress(): HasOne
    {
        return $this->hasOne(TaskProgress::class, 'task_id');
    }

    public function progressForUser(int $userId): HasOne
    {
        return $this->hasOne(TaskProgress::class, 'task_id')
            ->where('user_id', $userId);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(TaskNote::class, 'task_id');
    }
}