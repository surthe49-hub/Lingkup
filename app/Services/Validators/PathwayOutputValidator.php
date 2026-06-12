<?php

namespace App\Services\Validators;

use App\Services\Prompts\PathwayJsonSchema;

/**
 * 4-Layer validator untuk output Gemini pathway generation.
 *
 * Layer 1 (Structural): Cek struktur JSON sesuai schema
 * Layer 2 (Length):     Cek panjang text setiap field
 * Layer 3 (Content):    Cek kualitas konten (Bahasa Indonesia, verb di task title)
 * Layer 4 (Hallucination): Deteksi URL/tanggal/statistik fabricated
 *
 * Validation berjalan sequential — jika layer N fail, layer N+1 tidak dijalankan
 * untuk menghemat compute dan menghasilkan error message yang fokus.
 */
class PathwayOutputValidator
{
    // Length constraints (sinkron dengan schema description di PathwayJsonSchema)
    private const MAX_PATHWAY_TITLE = 200;
    private const MAX_PATHWAY_SUMMARY = 500;
    private const MAX_PHASE_TITLE = 150;
    private const MAX_PHASE_DESCRIPTION = 300;
    private const MAX_TASK_TITLE = 200;
    private const MAX_TASK_DESCRIPTION = 500;

    // Count constraints
    private const MIN_PHASES = 3;
    private const MAX_PHASES = 4;
    private const MIN_TASKS_PER_PHASE = 3;
    private const MAX_TASKS_PER_PHASE = 5;

    // Common English words yang seharusnya tidak muncul di output Bahasa Indonesia
    // (case-insensitive, word-boundary matching)
    private const ENGLISH_INDICATORS = [
        'will', 'should', 'must', 'have', 'with', 'from', 'this',
        'that', 'these', 'those', 'their', 'your',
        'about', 'above', 'below', 'between',
        'because', 'although',
    ];

    // Verb prefixes yang menandakan task actionable (Bahasa Indonesia)
    private const ACTION_VERBS = [
    // Verb dasar (kata kerja imperatif)
    'ambil', 'daftar', 'tulis', 'submit', 'hubungi', 'kirim', 'buat',
    'siapkan', 'persiapkan', 'pelajari', 'baca', 'cari', 'temukan',
    'ikuti', 'enroll', 'minta', 'mintalah', 'rancang', 'susun',
    'lakukan', 'jalani', 'kerjakan', 'selesaikan', 'capai',
    'tingkatkan', 'kembangkan', 'asah', 'latih', 'praktikkan',
    'identifikasi', 'evaluasi', 'review', 'gunakan', 'pakai',
    'bergabung', 'bergabunglah', 'volunteer', 'mulai', 'mulailah',
    'pilih', 'pilihlah', 'tentukan', 'tetapkan', 'jaga', 'pertahankan',

    // Verb prefix "Me-" (umum di output AI formal)
    'mengikuti', 'melakukan', 'menyusun', 'mempelajari', 'memperdalam',
    'membuat', 'menulis', 'mengembangkan', 'meningkatkan', 'membangun',
    'menghubungi', 'mempersiapkan', 'mengambil', 'mengirim', 'mengevaluasi',
    'mempraktikkan', 'memulai', 'meneruskan', 'mendalami', 'merancang',
    'mengidentifikasi', 'mencari', 'membaca', 'menentukan', 'mengasah',
    'melatih', 'menjalankan', 'menjalani', 'menyiapkan', 'mendaftar',
    'memilih', 'mengikutsertakan', 'memantau', 'menargetkan', 'memetakan',
    'mengelola', 'meraih', 'menjalin', 'memanfaatkan', 'menggali',

    // Verb yang kadang digunakan untuk task strategis
    'lengkapi', 'lengkapilah', 'finalisasi', 'optimalkan', 'maksimalkan',
    'kuatkan', 'perkuat', 'fokus', 'fokuskan', 'investasikan', 'alokasikan',

    // Noun yang masih actionable (e.g., "Riset universitas target")
    'riset', 'analisis', 'pemetaan', 'simulasi', 'konsultasi',
];

    /**
     * Validate full pathway output.
     */
    public function validate(array $output): ValidationResult
    {
        // Layer 1: Structural (paling critical, fail-fast)
        $structuralResult = $this->validateStructural($output);
        if (! $structuralResult->passed) {
            return $structuralResult;
        }

        // Layer 2: Length constraints
        $lengthResult = $this->validateLength($output);
        if (! $lengthResult->passed) {
            return $lengthResult;
        }

        // Layer 3: Content quality (warnings allowed, but heavy issues fail)
        $contentResult = $this->validateContent($output);
        if (! $contentResult->passed) {
            return $contentResult;
        }

        // Layer 4: Anti-hallucination
        $hallucinationResult = $this->validateAntiHallucination($output);
        if (! $hallucinationResult->passed) {
            return $hallucinationResult;
        }

        // Aggregate warnings dari semua layer
        $allWarnings = array_merge(
            $structuralResult->warnings,
            $lengthResult->warnings,
            $contentResult->warnings,
            $hallucinationResult->warnings,
        );

        return ValidationResult::pass($allWarnings);
    }

    // ============================================
    // Layer 1: Structural Validation
    // ============================================

    private function validateStructural(array $output): ValidationResult
    {
        $errors = [];

        // Root harus punya key "pathway"
        if (! isset($output['pathway'])) {
            return ValidationResult::fail(
                ValidationResult::LAYER_STRUCTURAL,
                ['Missing root key "pathway".'],
            );
        }

        $pathway = $output['pathway'];

        // Pathway required fields
        foreach (['title', 'summary', 'estimated_total_duration', 'phases'] as $field) {
            if (! isset($pathway[$field]) || $pathway[$field] === '') {
                $errors[] = "Missing or empty pathway.{$field}";
            }
        }

        if (! empty($errors)) {
            return ValidationResult::fail(ValidationResult::LAYER_STRUCTURAL, $errors);
        }

        // Phases must be array dengan count valid
        if (! is_array($pathway['phases'])) {
            return ValidationResult::fail(
                ValidationResult::LAYER_STRUCTURAL,
                ['pathway.phases must be an array.'],
            );
        }

        $phaseCount = count($pathway['phases']);
        if ($phaseCount < self::MIN_PHASES || $phaseCount > self::MAX_PHASES) {
            return ValidationResult::fail(
                ValidationResult::LAYER_STRUCTURAL,
                ["Phase count must be between " . self::MIN_PHASES . " and " . self::MAX_PHASES . ", got {$phaseCount}."],
            );
        }

        // Validate each phase
        foreach ($pathway['phases'] as $phaseIndex => $phase) {
            $phaseErrors = $this->validatePhaseStructure($phase, $phaseIndex);
            $errors = array_merge($errors, $phaseErrors);
        }

        if (! empty($errors)) {
            return ValidationResult::fail(ValidationResult::LAYER_STRUCTURAL, $errors);
        }

        return ValidationResult::pass();
    }

    private function validatePhaseStructure(mixed $phase, int $phaseIndex): array
    {
        $errors = [];
        $prefix = "Phase[{$phaseIndex}]";

        if (! is_array($phase)) {
            return ["{$prefix} must be an object."];
        }

        // Required fields
        foreach (['phase_order', 'title', 'description', 'estimated_duration', 'tasks'] as $field) {
            if (! isset($phase[$field])) {
                $errors[] = "{$prefix}.{$field} missing.";
            }
        }

        if (! empty($errors)) {
            return $errors;
        }

        // phase_order harus integer
        if (! is_int($phase['phase_order'])) {
            $errors[] = "{$prefix}.phase_order must be integer.";
        }

        // Tasks must be array
        if (! is_array($phase['tasks'])) {
            $errors[] = "{$prefix}.tasks must be array.";
            return $errors;
        }

        $taskCount = count($phase['tasks']);
        if ($taskCount < self::MIN_TASKS_PER_PHASE || $taskCount > self::MAX_TASKS_PER_PHASE) {
            $errors[] = "{$prefix}.tasks count must be between " . self::MIN_TASKS_PER_PHASE . " and " . self::MAX_TASKS_PER_PHASE . ", got {$taskCount}.";
        }

        // Validate each task
        foreach ($phase['tasks'] as $taskIndex => $task) {
            $taskErrors = $this->validateTaskStructure($task, $phaseIndex, $taskIndex);
            $errors = array_merge($errors, $taskErrors);
        }

        return $errors;
    }

    private function validateTaskStructure(mixed $task, int $phaseIndex, int $taskIndex): array
    {
        $errors = [];
        $prefix = "Phase[{$phaseIndex}].Task[{$taskIndex}]";

        if (! is_array($task)) {
            return ["{$prefix} must be an object."];
        }

        // Required fields
        $requiredFields = ['task_order', 'title', 'description', 'category', 'priority', 'estimated_duration'];
        foreach ($requiredFields as $field) {
            if (! isset($task[$field]) || $task[$field] === '') {
                $errors[] = "{$prefix}.{$field} missing or empty.";
            }
        }

        if (! empty($errors)) {
            return $errors;
        }

        // task_order must be integer
        if (! is_int($task['task_order'])) {
            $errors[] = "{$prefix}.task_order must be integer.";
        }

        // category enum check
        if (! in_array($task['category'], PathwayJsonSchema::getTaskCategories(), true)) {
            $errors[] = "{$prefix}.category invalid: '{$task['category']}'.";
        }

        // priority enum check
        if (! in_array($task['priority'], PathwayJsonSchema::getTaskPriorities(), true)) {
            $errors[] = "{$prefix}.priority invalid: '{$task['priority']}'.";
        }

        return $errors;
    }

    // ============================================
    // Layer 2: Length Validation
    // ============================================

    private function validateLength(array $output): ValidationResult
    {
        $errors = [];
        $pathway = $output['pathway'];

        if (mb_strlen($pathway['title']) > self::MAX_PATHWAY_TITLE) {
            $errors[] = "pathway.title exceeds " . self::MAX_PATHWAY_TITLE . " chars (got " . mb_strlen($pathway['title']) . ").";
        }

        if (mb_strlen($pathway['summary']) > self::MAX_PATHWAY_SUMMARY) {
            $errors[] = "pathway.summary exceeds " . self::MAX_PATHWAY_SUMMARY . " chars (got " . mb_strlen($pathway['summary']) . ").";
        }

        foreach ($pathway['phases'] as $phaseIndex => $phase) {
            if (mb_strlen($phase['title']) > self::MAX_PHASE_TITLE) {
                $errors[] = "Phase[{$phaseIndex}].title exceeds " . self::MAX_PHASE_TITLE . " chars.";
            }

            if (mb_strlen($phase['description']) > self::MAX_PHASE_DESCRIPTION) {
                $errors[] = "Phase[{$phaseIndex}].description exceeds " . self::MAX_PHASE_DESCRIPTION . " chars.";
            }

            foreach ($phase['tasks'] as $taskIndex => $task) {
                if (mb_strlen($task['title']) > self::MAX_TASK_TITLE) {
                    $errors[] = "Phase[{$phaseIndex}].Task[{$taskIndex}].title exceeds " . self::MAX_TASK_TITLE . " chars.";
                }

                if (mb_strlen($task['description']) > self::MAX_TASK_DESCRIPTION) {
                    $errors[] = "Phase[{$phaseIndex}].Task[{$taskIndex}].description exceeds " . self::MAX_TASK_DESCRIPTION . " chars.";
                }
            }
        }

        if (! empty($errors)) {
            return ValidationResult::fail(ValidationResult::LAYER_LENGTH, $errors);
        }

        return ValidationResult::pass();
    }

    // ============================================
    // Layer 3: Content Quality Validation
    // ============================================

    private function validateContent(array $output): ValidationResult
    {
        $errors = [];
        $warnings = [];
        $pathway = $output['pathway'];

        // Check for English code-switching di pathway.summary
        // (Heuristic: count common English words)
        $englishCountInSummary = $this->countEnglishIndicators($pathway['summary']);
        if ($englishCountInSummary >= 3) {
            $errors[] = "pathway.summary appears to contain too much English (found {$englishCountInSummary} indicators).";
        } elseif ($englishCountInSummary >= 1) {
            $warnings[] = "pathway.summary contains some English words ({$englishCountInSummary}).";
        }

        // Check task titles untuk action verb
        $tasksWithoutVerb = [];
        foreach ($pathway['phases'] as $phaseIndex => $phase) {
            foreach ($phase['tasks'] as $taskIndex => $task) {
                if (! $this->startsWithActionVerb($task['title'])) {
                    $tasksWithoutVerb[] = "Phase[{$phaseIndex}].Task[{$taskIndex}]: '{$task['title']}'";
                }
            }
        }

        // Toleransi: maks 30% task boleh tanpa verb di awal
        $totalTasks = $this->countTotalTasks($output);
        $tolerancePct = 0.3;
        if (count($tasksWithoutVerb) > ($totalTasks * $tolerancePct)) {
            $errors[] = "Too many tasks don't start with action verb (" . count($tasksWithoutVerb) . " out of {$totalTasks}).";
        } elseif (count($tasksWithoutVerb) > 0) {
            $warnings[] = count($tasksWithoutVerb) . " task(s) don't start with action verb.";
        }

        // Check duplicate phase titles
        $phaseTitles = array_column($pathway['phases'], 'title');
        $duplicatePhases = array_diff_assoc($phaseTitles, array_unique($phaseTitles));
        if (! empty($duplicatePhases)) {
            $errors[] = 'Duplicate phase titles found: ' . implode(', ', array_unique($duplicatePhases));
        }

        if (! empty($errors)) {
            return ValidationResult::fail(ValidationResult::LAYER_CONTENT, $errors, $warnings);
        }

        return ValidationResult::pass($warnings);
    }

    private function countEnglishIndicators(string $text): int
    {
        $count = 0;
        $lowerText = mb_strtolower($text);
        foreach (self::ENGLISH_INDICATORS as $word) {
            // Word boundary match
            if (preg_match_all('/\b' . preg_quote($word, '/') . '\b/u', $lowerText) > 0) {
                $count++;
            }
        }
        return $count;
    }

    private function startsWithActionVerb(string $title): bool
    {
        $firstWord = mb_strtolower(explode(' ', trim($title))[0] ?? '');
        return in_array($firstWord, self::ACTION_VERBS, true);
    }

    private function countTotalTasks(array $output): int
    {
        $total = 0;
        foreach ($output['pathway']['phases'] as $phase) {
            $total += count($phase['tasks'] ?? []);
        }
        return $total;
    }

    // ============================================
    // Layer 4: Anti-Hallucination Validation
    // ============================================

    private function validateAntiHallucination(array $output): ValidationResult
    {
        $errors = [];
        $warnings = [];
        $pathway = $output['pathway'];

        // Concat semua text untuk full scan
        $allText = $pathway['title'] . ' ' . $pathway['summary'] . ' ' . $pathway['estimated_total_duration'];
        foreach ($pathway['phases'] as $phase) {
            $allText .= ' ' . $phase['title'] . ' ' . $phase['description'] . ' ' . $phase['estimated_duration'];
            foreach ($phase['tasks'] as $task) {
                $allText .= ' ' . $task['title'] . ' ' . $task['description'] . ' ' . $task['estimated_duration'];
            }
        }

        // Detect URLs (forbidden per system prompt)
        if (preg_match_all('/https?:\/\/[^\s]+/i', $allText, $matches) > 0) {
            $errors[] = 'URLs found in output (forbidden): ' . implode(', ', array_slice($matches[0], 0, 3));
        }

        // Detect www.* domains
        if (preg_match_all('/\bwww\.[a-z0-9.-]+\.[a-z]{2,}/i', $allText, $matches) > 0) {
            $errors[] = 'Web domains found in output (forbidden): ' . implode(', ', array_slice($matches[0], 0, 3));
        }

        // Detect specific dates (e.g., "15 Maret 2027", "2027-03-15")
        // Pattern: tanggal numerik atau "tanggal bulan tahun"
        $datePatterns = [
            '/\b\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4}\b/',                       // 15/03/2027 or 15-03-2027
            '/\b\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}\b/',                          // 2027-03-15
            '/\b\d{1,2}\s+(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)\s+\d{4}\b/iu',
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match_all($pattern, $allText, $matches) > 0) {
                $warnings[] = 'Specific dates detected (potential hallucination): ' . implode(', ', array_slice($matches[0], 0, 3));
            }
        }

        // Detect suspicious statistics (e.g., "95% rate", "98% success")
        if (preg_match_all('/\b\d{2,3}\s*%[^,.]*\b(rate|success|berhasil|sukses|tingkat)/iu', $allText, $matches) > 0) {
            $errors[] = 'Suspicious statistics found: ' . implode(', ', array_slice($matches[0], 0, 3));
        }

        if (! empty($errors)) {
            return ValidationResult::fail(ValidationResult::LAYER_HALLUCINATION, $errors, $warnings);
        }

        return ValidationResult::pass($warnings);
    }
}