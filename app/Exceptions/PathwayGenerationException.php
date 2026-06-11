<?php

namespace App\Exceptions;

use Exception;

/**
 * Custom exception untuk semua failure case di pathway generation.
 *
 * Pakai named constructors agar caller dapat membedakan jenis failure
 * dengan jelas dan menerjemahkannya ke user-friendly message.
 *
 * Contoh penggunaan:
 *   throw PathwayGenerationException::timeout();
 *   throw PathwayGenerationException::apiError(500, $responseBody);
 */
class PathwayGenerationException extends Exception
{
    public const TYPE_TIMEOUT = 'timeout';
    public const TYPE_API_ERROR = 'api_error';
    public const TYPE_INVALID_JSON = 'invalid_json';
    public const TYPE_EMPTY_RESPONSE = 'empty_response';
    public const TYPE_VALIDATION_FAILED = 'validation_failed';
    public const TYPE_RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';

    public function __construct(
        public readonly string $type,
        string $message,
        public readonly array $context = [],
        ?Exception $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * API call exceeded timeout.
     */
    public static function timeout(int $timeoutSeconds = 30): self
    {
        return new self(
            type: self::TYPE_TIMEOUT,
            message: "Gemini API tidak merespons dalam {$timeoutSeconds} detik.",
            context: ['timeout_seconds' => $timeoutSeconds],
        );
    }

    /**
     * API mengembalikan status error (4xx atau 5xx).
     */
    public static function apiError(int $statusCode, string $body): self
    {
        return new self(
            type: self::TYPE_API_ERROR,
            message: "Gemini API mengembalikan error {$statusCode}.",
            context: [
                'status_code' => $statusCode,
                'response_body' => $body,
            ],
        );
    }

    /**
     * Response tidak dapat di-parse sebagai JSON valid.
     */
    public static function invalidJson(string $rawResponse, string $jsonError): self
    {
        return new self(
            type: self::TYPE_INVALID_JSON,
            message: "Response Gemini bukan JSON valid: {$jsonError}",
            context: [
                'raw_response' => substr($rawResponse, 0, 500),
                'json_error' => $jsonError,
            ],
        );
    }

    /**
     * Response sukses tapi kosong (tidak ada candidate).
     */
    public static function emptyResponse(): self
    {
        return new self(
            type: self::TYPE_EMPTY_RESPONSE,
            message: 'Gemini mengembalikan response kosong tanpa candidate.',
        );
    }

    /**
     * Output tidak lolos validasi schema/quality setelah retry.
     */
    public static function validationFailed(array $errors): self
    {
        return new self(
            type: self::TYPE_VALIDATION_FAILED,
            message: 'Output Gemini gagal validasi setelah semua retry.',
            context: ['validation_errors' => $errors],
        );
    }

    /**
     * User melampaui rate limit lokal (3x per target per minggu).
     */
    public static function rateLimitExceeded(int $current, int $limit, int $windowDays): self
    {
        return new self(
            type: self::TYPE_RATE_LIMIT_EXCEEDED,
            message: "Sudah {$current} generation dalam {$windowDays} hari terakhir. Limit: {$limit}.",
            context: [
                'current_count' => $current,
                'limit' => $limit,
                'window_days' => $windowDays,
            ],
        );
    }

    /**
     * User-friendly message untuk ditampilkan ke user.
     */
    public function userMessage(): string
    {
        return match ($this->type) {
            self::TYPE_TIMEOUT => 'Server AI sedang lambat. Silakan coba lagi dalam beberapa menit.',
            self::TYPE_API_ERROR => 'Layanan AI sedang mengalami gangguan. Tim kami sudah diberitahu.',
            self::TYPE_INVALID_JSON, self::TYPE_VALIDATION_FAILED => 'Gagal memproses respons AI. Silakan coba lagi.',
            self::TYPE_EMPTY_RESPONSE => 'AI tidak memberikan hasil. Silakan coba lagi.',
            self::TYPE_RATE_LIMIT_EXCEEDED => 'Kamu sudah mencapai batas generate pathway untuk minggu ini. Coba lagi minggu depan.',
            default => 'Terjadi kesalahan saat membuat pathway. Silakan coba lagi.',
        };
    }
}