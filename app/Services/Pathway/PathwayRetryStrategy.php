<?php

namespace App\Services\Pathway;

use App\Exceptions\PathwayGenerationException;

/**
 * Strategy service untuk retry mechanism pathway generation.
 *
 * Rules (D3 + D4 — Sprint 5):
 * - Selective retry per error type (D3 Opsi A)
 * - Max 2 retry attempts (D4 Opsi B) = 3 total attempts
 * - Backoff bervariasi per error type
 *
 * Standalone service: pure logic, no DB, no AI call.
 * Mudah di-unit-test.
 */
class PathwayRetryStrategy
{
    /**
     * Maksimum retry attempt (di luar attempt pertama).
     * Total attempts = 1 original + MAX_RETRIES.
     */
    public const MAX_RETRIES = 2;

    /**
     * Error type yang BOLEH di-retry (transient).
     */
    private const RETRYABLE_TYPES = [
        'timeout',           // TYPE_TIMEOUT — Gemini lambat, mungkin next call cepat
        'invalid_json',      // TYPE_INVALID_JSON — AI hiccup, retry sering resolve
        'empty_response',    // TYPE_EMPTY_RESPONSE — safety filter glitch
        'validation_failed', // TYPE_VALIDATION_FAILED — AI mungkin output lebih baik next attempt
    ];

    /**
     * Error type yang TIDAK BOLEH di-retry (permanent).
     */
    private const NON_RETRYABLE_TYPES = [
        'rate_limit_exceeded', // User-imposed limit, jangan bypass
    ];

    /**
     * Backoff dalam detik per error type.
     * Default: 2 detik jika type tidak terdaftar.
     */
    private const BACKOFF_SECONDS = [
        'timeout' => 5,            // Server load mungkin tinggi, kasih waktu
        'invalid_json' => 2,       // Quick retry, AI mungkin tinggal glitch
        'empty_response' => 3,     // Filter mungkin perlu reset
        'validation_failed' => 2,  // Quick retry dengan output baru
        'api_error' => 5,          // Conservative
    ];

    /**
     * Apakah error type ini layak di-retry?
     */
    public function shouldRetry(PathwayGenerationException $exception, int $attemptNumber): bool
    {
        // Sudah mencapai max retries
        if ($attemptNumber > self::MAX_RETRIES) {
            return false;
        }

        // Explicitly non-retryable
        if (in_array($exception->type, self::NON_RETRYABLE_TYPES, true)) {
            return false;
        }

        // Retryable types
        if (in_array($exception->type, self::RETRYABLE_TYPES, true)) {
            return true;
        }

        // API error: retry hanya untuk 5xx (server error)
        if ($exception->type === 'api_error') {
            return $this->isServerError($exception);
        }

        // Default: jangan retry untuk type yang tidak dikenal
        return false;
    }

    /**
     * Berapa detik harus tunggu sebelum retry.
     */
    public function getBackoffSeconds(PathwayGenerationException $exception): int
    {
        return self::BACKOFF_SECONDS[$exception->type] ?? 2;
    }

    /**
     * Total attempts maximum (1 original + MAX_RETRIES).
     */
    public function getMaxAttempts(): int
    {
        return 1 + self::MAX_RETRIES;
    }

    /**
     * Get human-readable retry decision info untuk logging.
     */
    public function getRetryDecision(PathwayGenerationException $exception, int $attemptNumber): array
    {
        $shouldRetry = $this->shouldRetry($exception, $attemptNumber);

        return [
            'should_retry' => $shouldRetry,
            'attempt_number' => $attemptNumber,
            'max_retries' => self::MAX_RETRIES,
            'error_type' => $exception->type,
            'backoff_seconds' => $shouldRetry ? $this->getBackoffSeconds($exception) : 0,
            'reason' => $this->getDecisionReason($exception, $shouldRetry, $attemptNumber),
        ];
    }

        /**
         * Cek apakah api_error adalah server-side (5xx) yang retryable.
         *
         * Robust parsing: cari pattern HTTP status code 5xx di mana saja di message.
         * Format-agnostic: works dengan "status 503", "error 503", "503 Service Unavailable", etc.
         */
        private function isServerError(PathwayGenerationException $exception): bool
        {
            $message = $exception->getMessage();

            // Cari angka 3-digit di rentang 500-599 (HTTP 5xx) di mana saja di message
            if (preg_match('/\b(5\d{2})\b/', $message, $matches)) {
                $statusCode = (int) $matches[1];
                return $statusCode >= 500 && $statusCode < 600;
            }

            return false;
        }

    /**
     * Penjelasan reason untuk logging/debugging.
     */
    private function getDecisionReason(
        PathwayGenerationException $exception,
        bool $shouldRetry,
        int $attemptNumber,
    ): string {
        if ($attemptNumber > self::MAX_RETRIES) {
            return "Max retries ({self::MAX_RETRIES}) exceeded.";
        }

        if (in_array($exception->type, self::NON_RETRYABLE_TYPES, true)) {
            return "Error type '{$exception->type}' is permanent, no retry.";
        }

        if ($shouldRetry) {
            $backoff = $this->getBackoffSeconds($exception);
            return "Retryable error '{$exception->type}', backoff {$backoff}s, attempt {$attemptNumber}/" . self::MAX_RETRIES;
        }

        return "Error type '{$exception->type}' not in retryable list.";
    }
}