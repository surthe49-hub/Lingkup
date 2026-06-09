<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PathwayPhase extends Model
{
    use HasFactory;

    protected $fillable = [
        'pathway_id',
        'phase_order',
        'title',
        'description',
        'estimated_duration',
    ];

    protected function casts(): array
    {
        return [
            'phase_order' => 'integer',
        ];
    }

    // ============================================
    // Relationships
    // ============================================

    public function pathway(): BelongsTo
    {
        return $this->belongsTo(Pathway::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(PathwayTask::class, 'phase_id')
            ->orderBy('task_order');
    }
}