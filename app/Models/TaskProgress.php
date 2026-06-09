<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskProgress extends Model
{
    use HasFactory;

    /**
     * Karena tabel-nya 'task_progress' (bukan 'task_progresses'),
     * Laravel akan mendeteksinya dengan benar berdasarkan inflector.
     * Tetapi untuk eksplisitas, kita set manual.
     */
    protected $table = 'task_progress';

    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeNotStarted($query)
    {
        return $query->where('status', 'belum_dimulai');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'sedang_dikerjakan');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ============================================
    // Lifecycle Helpers
    // ============================================

    public function markAsInProgress(): void
    {
        $this->update([
            'status' => 'sedang_dikerjakan',
            'started_at' => $this->started_at ?? now(),
        ]);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'selesai',
            'started_at' => $this->started_at ?? now(),
            'completed_at' => now(),
        ]);
    }

    public function reset(): void
    {
        $this->update([
            'status' => 'belum_dimulai',
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    // ============================================
    // Relationships
    // ============================================

    public function task(): BelongsTo
    {
        return $this->belongsTo(PathwayTask::class, 'task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}