<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

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