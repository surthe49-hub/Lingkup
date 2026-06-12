<?php

namespace App\Services\Prompts;

use App\Models\Profile;
use App\Models\Target;

/**
 * Builder untuk merangkai user prompt dari data Profile + Target.
 *
 * Output prompt mengikuti template yang sudah divalidasi
 * di Sprint 4 preparation kit. Format strict — jangan refactor
 * tanpa re-validation di Google AI Studio.
 *
 * Strategi context injection:
 * - WAJIB inject: major, education_level, semester, gpa, english_level,
 *   current_skills, interests, target_country
 * - OPSIONAL inject (skip jika kosong): english_test_*, other_languages,
 *   organization_experience, career_goal
 * - TIDAK inject: id, user_id, timestamps (privacy & noise)
 *
 * Untuk Target:
 * - WAJIB: name, country, education_level, program_type, requirements_summary,
 *   typical_deadline
 * - TIDAK inject: structured_requirements (redundant dengan summary),
 *   official_url, is_active, timestamps
 */
class PathwayUserPromptBuilder
{
    /**
     * Build user prompt dari Profile dan Target.
     *
     * @throws \InvalidArgumentException Jika required field profile/target null.
     */
    public function build(Profile $profile, Target $target): string
    {
        $this->validateRequiredFields($profile, $target);

        $profileSection = $this->buildProfileSection($profile);
        $targetSection = $this->buildTargetSection($target);
        $instruction = $this->buildInstructionSection();

        return implode("\n\n", [$profileSection, $targetSection, $instruction]);
    }

    /**
     * Guard: pastikan required field tidak null sebelum build prompt.
     */
    private function validateRequiredFields(Profile $profile, Target $target): void
    {
        $missing = [];

        foreach (['major', 'education_level', 'semester', 'gpa', 'english_level'] as $field) {
            if (empty($profile->{$field})) {
                $missing[] = "profile.{$field}";
            }
        }

        foreach (['name', 'country', 'education_level', 'program_type', 'requirements_summary'] as $field) {
            if (empty($target->{$field})) {
                $missing[] = "target.{$field}";
            }
        }

        if (! empty($missing)) {
            throw new \InvalidArgumentException(
                'Cannot build pathway prompt. Missing required fields: ' . implode(', ', $missing)
            );
        }
    }

    /**
     * Section [PROFIL USER] — render dari Profile model.
     */
    private function buildProfileSection(Profile $profile): string
    {
        $lines = ['[PROFIL USER]'];

        // Wajib
        $lines[] = "- Jurusan: {$profile->major}";
        $lines[] = "- Jenjang: {$profile->education_level}, Semester {$profile->semester}";
        $lines[] = "- IPK: {$profile->gpa}";
        $lines[] = "- Tingkat Bahasa Inggris: " . ucfirst($profile->english_level);

        // English test (opsional, render hanya jika ada)
        if (! empty($profile->english_test_type) && ! empty($profile->english_test_score)) {
            $testType = str_replace('_', ' ', $profile->english_test_type);
            $lines[] = "- Sertifikasi Inggris: {$testType} - Skor {$profile->english_test_score}";
        } else {
            $lines[] = "- Sertifikasi Inggris: (belum ada)";
        }

        // Other languages (opsional)
        if (! empty($profile->other_languages) && is_array($profile->other_languages)) {
            $formatted = $this->formatOtherLanguages($profile->other_languages);
            if (! empty($formatted)) {
                $lines[] = "- Bahasa Lain: {$formatted}";
            }
        }

        // Skills (wajib, walaupun bisa kosong)
        $skills = ! empty($profile->current_skills) && is_array($profile->current_skills)
            ? implode(', ', $profile->current_skills)
            : '(belum diisi)';
        $lines[] = "- Skills: {$skills}";

        // Organization experience (opsional)
        if (! empty($profile->organization_experience)) {
            // Trim ke 200 char untuk tidak terlalu panjang
            $experience = mb_strlen($profile->organization_experience) > 200
                ? mb_substr($profile->organization_experience, 0, 200) . '...'
                : $profile->organization_experience;
            $lines[] = "- Pengalaman Organisasi: {$experience}";
        }

        // Interests (wajib)
        $interests = ! empty($profile->interests) && is_array($profile->interests)
            ? implode(', ', $profile->interests)
            : '(belum diisi)';
        $lines[] = "- Minat: {$interests}";

        // Target country preference (opsional)
        if (! empty($profile->target_country)) {
            $lines[] = "- Negara Tujuan (preferensi): {$profile->target_country}";
        }

        // Career goal (opsional)
        if (! empty($profile->career_goal)) {
            $careerGoal = mb_strlen($profile->career_goal) > 200
                ? mb_substr($profile->career_goal, 0, 200) . '...'
                : $profile->career_goal;
            $lines[] = "- Tujuan Karier: {$careerGoal}";
        }

        return implode("\n", $lines);
    }

    /**
     * Section [TARGET YANG DIPILIH] — render dari Target model.
     */
    private function buildTargetSection(Target $target): string
    {
        $lines = ['[TARGET YANG DIPILIH]'];

        $lines[] = "- Nama: {$target->name}";
        $lines[] = "- Negara: {$target->country}";
        $lines[] = "- Jenjang: {$target->education_level}";
        $lines[] = "- Tipe Program: " . ucfirst(str_replace('_', ' ', $target->program_type));
        $lines[] = "- Persyaratan: {$target->requirements_summary}";

        if (! empty($target->typical_deadline)) {
            $lines[] = "- Periode Pendaftaran: {$target->typical_deadline}";
        }

        return implode("\n", $lines);
    }

    /**
     * Section [INSTRUKSI] — task instruction statis.
     */
    private function buildInstructionSection(): string
    {
        return <<<'TEXT'
        [INSTRUKSI]
        Susun roadmap persiapan personal untuk user ini menuju target tersebut.
        Roadmap harus:
        1. Dimulai dari posisi user saat ini (perhatikan gap antara profil dan persyaratan target).
        2. Berisi 3 sampai 4 fase persiapan yang logis.
        3. Setiap fase berisi 3 sampai 5 task yang actionable.
        4. Total durasi roadmap realistis: 6 sampai 18 bulan.
        5. Prioritaskan task yang menutup gap terbesar dulu.

        Kembalikan dalam format JSON sesuai schema.
        TEXT;
    }

    /**
     * Format other_languages array ke string readable.
     *
     * Input: [{"lang": "Korean", "level": "beginner"}, ...]
     * Output: "Korean (Beginner), Mandarin (Intermediate)"
     */
    private function formatOtherLanguages(array $languages): string
    {
        $formatted = [];
        foreach ($languages as $lang) {
            if (empty($lang['lang'])) continue;
            $level = ! empty($lang['level']) ? ' (' . ucfirst($lang['level']) . ')' : '';
            $formatted[] = $lang['lang'] . $level;
        }
        return implode(', ', $formatted);
    }
}