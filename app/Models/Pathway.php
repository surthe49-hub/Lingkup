<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pathway extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'target_id',
        'title',
        'summary',
        'estimated_total_duration',
        'status',
        'generation_count',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'generation_count' => 'integer',
            'generated_at' => 'datetime',
        ];
    }
    
    // ============================================
    // Relationships
    // ============================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(PathwayPhase::class)->orderBy('phase_order');
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            PathwayTask::class,
            PathwayPhase::class,
            'pathway_id',   // FK di pathway_phases
            'phase_id',     // FK di pathway_tasks
            'id',           // PK di pathways
            'id'            // PK di pathway_phases
        );
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ============================================
    // Accessor: Progress Percentage
    // ============================================

    public function getProgressPercentageAttribute(): float
    {
        $totalTasks = $this->tasks()->count();

        if ($totalTasks === 0) {
            return 0.0;
        }

        $completedTasks = TaskProgress::query()
            ->whereIn('task_id', $this->tasks()->pluck('id'))
            ->where('user_id', $this->user_id)
            ->where('status', 'selesai')
            ->count();

        return round(($completedTasks / $totalTasks) * 100, 2);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class);
    }

    public function generationLogs(): HasMany
    {
        return $this->hasMany(PathwayGenerationLog::class);
    }
}