<?php

namespace App\Services\AI;

use App\Exceptions\PathwayGenerationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Implementasi AiClient untuk Google Gemini.
 *
 * Menggunakan Laravel HTTP Client native (Guzzle under the hood).
 * Tidak menggunakan SDK eksternal agar dependency footprint minimal.
 *
 * REST API reference:
 * https://ai.google.dev/api/generate-content
 */
class GeminiClient implements AiClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model,
        private readonly string $baseUrl,
        private readonly int $timeoutSeconds,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function generateStructured(
        string $systemPrompt,
        string $userPrompt,
        array $jsonSchema,
    ): array {
        $endpoint = "{$this->baseUrl}/models/{$this->model}:generateContent";

        $payload = $this->buildPayload($systemPrompt, $userPrompt, $jsonSchema);

        Log::info('Gemini API request', [
            'model' => $this->model,
            'payload_size_bytes' => strlen(json_encode($payload)),
        ]);

        try {
            $response = Http::timeout($this->timeoutSeconds)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->withQueryParameters([
                    'key' => $this->apiKey,
                ])
                ->post($endpoint, $payload);
        } catch (ConnectionException $e) {
            Log::warning('Gemini API timeout', [
                'timeout' => $this->timeoutSeconds,
                'error' => $e->getMessage(),
            ]);

            throw PathwayGenerationException::timeout($this->timeoutSeconds);
        } catch (Throwable $e) {
            Log::error('Gemini API unexpected error', [
                'error' => $e->getMessage(),
            ]);

            throw PathwayGenerationException::apiError(0, $e->getMessage());
        }

        if (! $response->successful()) {
            Log::warning('Gemini API returned error status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw PathwayGenerationException::apiError(
                $response->status(),
                $response->body(),
            );
        }

        Log::info('Gemini API success', [
            'status' => $response->status(),
        ]);

        return $this->parseResponse($response->json());
    }

    /**
     * Build payload sesuai spec Gemini API.
     *
     * Gunakan field "systemInstruction" untuk system prompt
     * (didukung Gemini 2.5+ family).
     */
    private function buildPayload(
        string $systemPrompt,
        string $userPrompt,
        array $jsonSchema,
    ): array {
        return [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $systemPrompt],
                ],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $userPrompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema' => $jsonSchema,
                'temperature' => 0.7,
                'maxOutputTokens' => 4096,
            ],
        ];
    }

    /**
     * Parse response Gemini ke PHP array.
     *
     * Struktur response Gemini:
     * {
     *   "candidates": [
     *     {
     *       "content": {
     *         "parts": [
     *           { "text": "{...JSON string...}" }
     *         ]
     *       }
     *     }
     *   ]
     * }
     */
    private function parseResponse(array $responseJson): array
    {
        $text = data_get($responseJson, 'candidates.0.content.parts.0.text');

        if ($text === null || $text === '') {
            Log::warning('Gemini returned empty response', [
                'response' => $responseJson,
            ]);

            throw PathwayGenerationException::emptyResponse();
        }

        $decoded = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Gemini response is not valid JSON', [
                'raw_text' => substr($text, 0, 500),
                'json_error' => json_last_error_msg(),
            ]);

            throw PathwayGenerationException::invalidJson(
                $text,
                json_last_error_msg(),
            );
        }

        return $decoded;
    }
}