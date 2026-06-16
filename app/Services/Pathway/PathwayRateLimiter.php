<?php

namespace App\Services\Pathway;

use App\Models\PathwayGenerationLog;
use App\Models\Target;
use App\Models\User;
use Carbon\Carbon;

/**
 * Service untuk rate limiting pathway generation.
 *
 * Aturan (D5 Opsi A — Sprint 5):
 * - Max 3 successful generation per target per user per 7-day rolling window
 * - Source of truth: pathway_generation_logs table dengan status='success'
 *
 * Standalone service: tidak depend pada AI call, mudah di-test.
 * Phase 3B awalnya: dipakai sebagai precheck di PathwayGenerationService
 * sebelum invoke AI client. Failure = throw exception sebelum cost AI.
 */
class PathwayRateLimiter
{
    /**
     * Maksimum generation sukses per target per user dalam window.
     */
    public const MAX_GENERATIONS_PER_WINDOW = 3;

    /**
     * Window rolling dalam hari.
     */
    public const WINDOW_DAYS = 7;

    /**
     * Check apakah user boleh generate pathway untuk target ini.
     *
     * @return bool true jika allowed, false jika limit terlampaui
     */
    public function canGenerate(User $user, Target $target): bool
    {
        return $this->getRemainingGenerations($user, $target) > 0;
    }

    /**
     * Berapa kali user sudah generate untuk target ini di window saat ini.
     */
    public function getCurrentUsage(User $user, Target $target): int
    {
        return PathwayGenerationLog::where('user_id', $user->id)
            ->where('target_id', $target->id)
            ->where('status', 'success')
            ->where('created_at', '>=', $this->getWindowStart())
            ->count();
    }

    /**
     * Berapa generation tersisa untuk user di target ini.
     */
    public function getRemainingGenerations(User $user, Target $target): int
    {
        $current = $this->getCurrentUsage($user, $target);
        return max(0, self::MAX_GENERATIONS_PER_WINDOW - $current);
    }

    /**
     * Kapan user akan dapat reset (oldest log dalam window akan expire).
     *
     * Return null jika user belum hit limit.
     */
    public function getResetAt(User $user, Target $target): ?Carbon
    {
        if ($this->canGenerate($user, $target)) {
            return null; // Belum hit limit
        }

        // Cari log paling lama dalam window — kapan dia keluar window
        $oldestLog = PathwayGenerationLog::where('user_id', $user->id)
            ->where('target_id', $target->id)
            ->where('status', 'success')
            ->where('created_at', '>=', $this->getWindowStart())
            ->orderBy('created_at', 'asc')
            ->first();

        if (! $oldestLog) {
            return null;
        }

        // Reset time = oldest_log.created_at + window_days
        return $oldestLog->created_at->addDays(self::WINDOW_DAYS);
    }

    /**
     * Human-readable message untuk user saat hit rate limit.
     */
    public function getRateLimitMessage(User $user, Target $target): string
    {
        $resetAt = $this->getResetAt($user, $target);

        if (! $resetAt) {
            return 'Anda telah mencapai batas generation untuk target ini.';
        }

        $resetIn = $resetAt->diffForHumans(null, true);
        return "Anda telah mencapai batas " . self::MAX_GENERATIONS_PER_WINDOW
            . " generation per minggu untuk target ini. "
            . "Silakan coba lagi dalam {$resetIn}.";
    }

    /**
     * Get window start datetime (now - WINDOW_DAYS).
     */
    private function getWindowStart(): Carbon
    {
        return now()->subDays(self::WINDOW_DAYS);
    }
}