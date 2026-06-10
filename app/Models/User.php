<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================================
    // Helper Methods
    // ============================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // ============================================
    // Relationships
    // ============================================

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function userTargets(): HasMany
    {
        return $this->hasMany(UserTarget::class);
    }
    /**
     * Relasi ke target aktif user (1 user = 1 active target).
     *
     * Walaupun tabel user_targets dirancang many-to-many,
     * business rule Sprint 3: 1 user = 1 row.
     * latest('selected_at') sebagai defensive jika somehow ada > 1 row.
     */
    public function userTarget(): HasOne
    {
        return $this->hasOne(UserTarget::class)->latest('selected_at');
    }
    public function selectedTargets(): BelongsToMany
    {
        return $this->belongsToMany(Target::class, 'user_targets')
            ->withPivot('selected_at')
            ->withTimestamps();
    }

    public function pathways(): HasMany
    {
        return $this->hasMany(Pathway::class);
    }

    public function taskProgress(): HasMany
    {
        return $this->hasMany(TaskProgress::class);
    }

    public function taskNotes(): HasMany
    {
        return $this->hasMany(TaskNote::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function pathwayGenerationLogs(): HasMany
    {
        return $this->hasMany(PathwayGenerationLog::class);
    }
}