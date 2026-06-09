<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_id',
        'selected_at',
    ];

    protected function casts(): array
    {
        return [
            'selected_at' => 'datetime',
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
}