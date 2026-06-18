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
    
    /**
     * Pathway aktif user (1:1 dengan filter status='active').
     *
     * Phase 3A: hard delete pathway lama saat regenerate, sehingga
     * hanya ada 1 active pathway per user.
     * Phase 3B akan upgrade ke proper archive logic.
     */
    public function pathway(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Pathway::class)->where('status', 'active');
    }

    /**
     * Semua pathway user (historical, untuk Phase 3B+).
     */
    public function pathways(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pathway::class);
    }

    /**
     * Generation logs dari user (untuk rate limiting & analytics Phase 3B).
     */
    public function pathwayGenerationLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PathwayGenerationLog::class);
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

    /**
     * Cek apakah pathway aktif user mismatch dengan target aktif user.
     *
     * Mismatch terjadi jika:
     * - User punya active pathway
     * - User punya user_target
     * - pathway.target_id != user_target.target_id
     *
     * @return bool
     */
    public function hasPathwayTargetMismatch(): bool
    {
        $activePathway = $this->pathway;
        $userTarget = $this->userTarget;

        if (! $activePathway || ! $userTarget) {
            return false;
        }

        return $activePathway->target_id !== $userTarget->target_id;
    }

    /**
     * Get informasi mismatch (untuk banner UI).
     *
     * Return null jika tidak ada mismatch.
     *
     * @return array{
     * pathway_target: \App\Models\Target,
     * current_target: \App\Models\Target,
     * pathway_id: int,
     * }|null
     */
    public function getPathwayTargetMismatchInfo(): ?array
    {
        if (! $this->hasPathwayTargetMismatch()) {
            return null;
        }

        $activePathway = $this->pathway;
        $userTarget = $this->userTarget;

        // Load relationships jika belum di-load
        if (! $activePathway->relationLoaded('target')) {
            $activePathway->load('target');
        }
        if (! $userTarget->relationLoaded('target')) {
            $userTarget->load('target');
        }

        return [
            'pathway_target' => $activePathway->target,
            'current_target' => $userTarget->target,
            'pathway_id' => $activePathway->id,
        ];
    }
}