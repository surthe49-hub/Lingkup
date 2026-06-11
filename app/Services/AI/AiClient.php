<?php

namespace App\Services\AI;

use App\Exceptions\PathwayGenerationException;

/**
 * Kontrak untuk AI provider yang menghasilkan output JSON terstruktur.
 *
 * Implementasi: GeminiClient (default), bisa di-swap ke OpenAIClient
 * di masa depan tanpa mengubah caller (PathwayGenerationService).
 */
interface AiClient
{
    /**
     * Generate output JSON terstruktur dari AI.
     *
     * @param  string  $systemPrompt  Instruksi peran dan batasan (statis per use case).
     * @param  string  $userPrompt    Konten dinamis (context user + task).
     * @param  array   $jsonSchema    JSON schema untuk enforce struktur output.
     * @return array Decoded JSON response dari AI.
     *
     * @throws PathwayGenerationException Jika timeout, API error, invalid JSON, atau empty response.
     */
    public function generateStructured(
        string $systemPrompt,
        string $userPrompt,
        array $jsonSchema,
    ): array;
}