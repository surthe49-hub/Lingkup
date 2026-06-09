<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PathwayGenerationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pathway_id',
        'target_id',
        'model_used',
        'prompt_tokens',
        'completion_tokens',
        'cost_idr',
        'latency_ms',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'prompt_tokens' => 'integer',
            'completion_tokens' => 'integer',
            'cost_idr' => 'decimal:2',
            'latency_ms' => 'integer',
        ];
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['timeout', 'api_error', 'validation_failed']);
    }

    public function scopeWithinDays($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ============================================
    // Relationships
    // ============================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pathway(): BelongsTo
    {
        return $this->belongsTo(Pathway::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class);
    }
}