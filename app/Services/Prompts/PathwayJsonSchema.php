<?php

namespace App\Services\Prompts;

/**
 * JSON Schema untuk responseSchema parameter Gemini API.
 *
 * Schema ini di-enforce di sisi Gemini sebagai first defense
 * validation (Layer 1 dari 4 validation layers).
 *
 * Mengikuti subset OpenAPI 3.0 schema yang didukung Gemini.
 * Reference: https://ai.google.dev/api/generate-content#schema
 */
class PathwayJsonSchema
{
    /**
     * Enum values untuk task category. Harus sinkron dengan
     * column `category` di tabel pathway_tasks (enum constraint).
     */
    private const TASK_CATEGORIES = [
        'language',
        'academic',
        'document',
        'experience',
        'test',
        'application',
        'other',
    ];

    /**
     * Enum values untuk task priority.
     */
    private const TASK_PRIORITIES = ['high', 'medium', 'low'];

    public static function get(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'pathway' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => [
                            'type' => 'string',
                            'description' => 'Judul pathway. Maks 200 karakter. Sebutkan nama target.',
                        ],
                        'summary' => [
                            'type' => 'string',
                            'description' => 'Ringkasan strategi roadmap dalam 1-3 kalimat. Maks 500 karakter.',
                        ],
                        'estimated_total_duration' => [
                            'type' => 'string',
                            'description' => 'Estimasi total durasi roadmap, contoh: "12 bulan", "18 bulan".',
                        ],
                        'phases' => [
                            'type' => 'array',
                            'minItems' => 3,
                            'maxItems' => 4,
                            'items' => [
                                'type' => 'object',
                                'properties' => [
                                    'phase_order' => [
                                        'type' => 'integer',
                                        'description' => 'Urutan fase, dimulai dari 1.',
                                    ],
                                    'title' => [
                                        'type' => 'string',
                                        'description' => 'Judul fase. Maks 150 karakter.',
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                        'description' => 'Deskripsi tujuan dan fokus fase ini. Maks 300 karakter.',
                                    ],
                                    'estimated_duration' => [
                                        'type' => 'string',
                                        'description' => 'Estimasi durasi fase, contoh: "3 bulan".',
                                    ],
                                    'tasks' => [
                                        'type' => 'array',
                                        'minItems' => 3,
                                        'maxItems' => 5,
                                        'items' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'task_order' => [
                                                    'type' => 'integer',
                                                    'description' => 'Urutan task dalam fase, dimulai dari 1.',
                                                ],
                                                'title' => [
                                                    'type' => 'string',
                                                    'description' => 'Judul task. Mulai dengan kata kerja. Maks 200 karakter.',
                                                ],
                                                'description' => [
                                                    'type' => 'string',
                                                    'description' => 'Penjelasan detail task: apa yang dilakukan, kenapa penting. Maks 500 karakter.',
                                                ],
                                                'category' => [
                                                    'type' => 'string',
                                                    'enum' => self::TASK_CATEGORIES,
                                                    'description' => 'Kategori task.',
                                                ],
                                                'priority' => [
                                                    'type' => 'string',
                                                    'enum' => self::TASK_PRIORITIES,
                                                    'description' => 'Tingkat prioritas task.',
                                                ],
                                                'estimated_duration' => [
                                                    'type' => 'string',
                                                    'description' => 'Estimasi durasi task, contoh: "2 minggu".',
                                                ],
                                            ],
                                            'required' => [
                                                'task_order',
                                                'title',
                                                'description',
                                                'category',
                                                'priority',
                                                'estimated_duration',
                                            ],
                                        ],
                                    ],
                                ],
                                'required' => [
                                    'phase_order',
                                    'title',
                                    'description',
                                    'estimated_duration',
                                    'tasks',
                                ],
                            ],
                        ],
                    ],
                    'required' => [
                        'title',
                        'summary',
                        'estimated_total_duration',
                        'phases',
                    ],
                ],
            ],
            'required' => ['pathway'],
        ];
    }

    /**
     * Helper untuk akses enum task categories di tempat lain.
     */
    public static function getTaskCategories(): array
    {
        return self::TASK_CATEGORIES;
    }

    /**
     * Helper untuk akses enum task priorities di tempat lain.
     */
    public static function getTaskPriorities(): array
    {
        return self::TASK_PRIORITIES;
    }
}