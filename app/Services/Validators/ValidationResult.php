<?php

namespace App\Services\Validators;

/**
 * Value object hasil validasi pathway output.
 *
 * Immutable. Pakai factory methods untuk constructing:
 *   ValidationResult::pass()
 *   ValidationResult::fail($layer, $errors)
 */
class ValidationResult
{
    public const LAYER_STRUCTURAL = 'structural';
    public const LAYER_LENGTH = 'length';
    public const LAYER_CONTENT = 'content';
    public const LAYER_HALLUCINATION = 'hallucination';

    /**
     * @param  bool   $passed       Apakah validation lolos
     * @param  string|null  $failedLayer  Layer mana yang gagal (null jika passed)
     * @param  array  $errors       List error messages
     * @param  array  $warnings     List warnings (tidak block, tapi worth noting)
     */
    private function __construct(
        public readonly bool $passed,
        public readonly ?string $failedLayer,
        public readonly array $errors,
        public readonly array $warnings = [],
    ) {
    }

    /**
     * Factory: validation pass.
     *
     * Warnings boleh ada (tidak block), tapi tidak ada error.
     */
    public static function pass(array $warnings = []): self
    {
        return new self(
            passed: true,
            failedLayer: null,
            errors: [],
            warnings: $warnings,
        );
    }

    /**
     * Factory: validation fail.
     *
     * Caller WAJIB specify layer mana yang fail dan apa errornya.
     */
    public static function fail(string $layer, array $errors, array $warnings = []): self
    {
        return new self(
            passed: false,
            failedLayer: $layer,
            errors: $errors,
            warnings: $warnings,
        );
    }

    /**
     * Apakah ini failure di critical layer (structural, length, hallucination)?
     * Content quality fail lebih ringan dan bisa di-tolerate.
     */
    public function isCriticalFailure(): bool
    {
        return ! $this->passed && in_array($this->failedLayer, [
            self::LAYER_STRUCTURAL,
            self::LAYER_LENGTH,
            self::LAYER_HALLUCINATION,
        ], true);
    }

    /**
     * Convert ke array untuk logging.
     */
    public function toArray(): array
    {
        return [
            'passed' => $this->passed,
            'failed_layer' => $this->failedLayer,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
        ];
    }
}