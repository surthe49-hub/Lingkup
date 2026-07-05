<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Override default plural inflection.
     * Laravel default akan ke 'feedbacks', tapi tabel kita 'feedback'.
     */
    protected $table = 'feedback';

    protected $fillable = [
        'pathway_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'read_at' => 'datetime',
        ];
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeWithRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopeLowRated($query)
    {
        return $query->where('rating', '<=', 2);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        $this->read_at = now();
        $this->save();
    }

    public function markAsUnread(): void
    {
        $this->read_at = null;
        $this->save();
    }

    // ============================================
    // Relationships
    // ============================================

    public function pathway(): BelongsTo
    {
        return $this->belongsTo(Pathway::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}