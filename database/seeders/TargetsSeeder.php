<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Seeder;

class TargetsSeeder extends Seeder
{
    /**
     * Seed 8 target beasiswa untuk Sprint 3.
     * Pakai firstOrCreate agar idempotent (aman di-run berkali-kali).
     */
    public function run(): void
    {
        $targets = [
            [
                'name' => 'MEXT Scholarship',
                'country' => 'Jepang',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa penuh dari pemerintah Jepang untuk studi S1/S2/S3 di universitas Jepang. Mencakup biaya kuliah, tunjangan hidup bulanan, dan tiket pesawat. Kandidat harus berusia di bawah 35 tahun, memiliki IPK minimal 3.0, dan menunjukkan minat akademik yang kuat pada bidang pilihan.',
                'structured_requirements' => [
                    'age_max' => 35,
                    'gpa_min' => 3.0,
                    'language' => 'Japanese N2 atau English equivalent',
                    'documents' => ['Transcript', 'Recommendation Letter', 'Research Plan', 'Health Certificate'],
                ],
                'typical_deadline' => 'April - Mei setiap tahun',
                'official_url' => 'https://www.id.emb-japan.go.jp/sch.html',
                'is_active' => true,
            ],
            [
                'name' => 'LPDP (Lembaga Pengelola Dana Pendidikan)',
                'country' => 'Indonesia / Global',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa dari pemerintah Indonesia untuk studi S2/S3 di dalam dan luar negeri. WNI dengan IPK minimal 3.0 (S1) atau 3.25 (S2). Wajib pulang dan mengabdi di Indonesia setelah studi. Tersedia banyak skema: Reguler, Afirmasi, Targeted.',
                'structured_requirements' => [
                    'citizenship' => 'WNI',
                    'gpa_min_s1' => 3.0,
                    'gpa_min_s2' => 3.25,
                    'language' => 'IELTS 6.5 / TOEFL iBT 80',
                    'documents' => ['LoA / LoR', 'Essay Kontribusi', 'Surat Komitmen', 'Sertifikat Bahasa'],
                ],
                'typical_deadline' => 'Februari dan Juli (2 batch per tahun)',
                'official_url' => 'https://lpdp.kemenkeu.go.id',
                'is_active' => true,
            ],
            [
                'name' => 'Australia Awards Scholarship (AAS)',
                'country' => 'Australia',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa penuh dari pemerintah Australia untuk warga negara berkembang termasuk Indonesia. Mencakup biaya kuliah, tunjangan hidup, asuransi kesehatan, dan tiket pesawat PP. Fokus pada bidang yang mendukung pembangunan Indonesia.',
                'structured_requirements' => [
                    'gpa_min' => 2.9,
                    'work_experience_min_years' => 2,
                    'language' => 'IELTS 6.5 (no band below 6.0)',
                    'priority_fields' => ['Economic Policy', 'Education', 'Health', 'Governance'],
                ],
                'typical_deadline' => 'Februari - April setiap tahun',
                'official_url' => 'https://www.australiaawardsindonesia.org',
                'is_active' => true,
            ],
            [
                'name' => 'Fulbright Scholarship',
                'country' => 'Amerika Serikat',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa prestisius dari pemerintah AS untuk studi S2/S3 di universitas Amerika. Mencakup biaya kuliah penuh, tunjangan bulanan, asuransi, dan tiket pesawat. Sangat kompetitif, kandidat harus menunjukkan academic excellence dan leadership potential.',
                'structured_requirements' => [
                    'gpa_min' => 3.0,
                    'language' => 'TOEFL iBT 90+ atau IELTS 6.5+',
                    'documents' => ['Personal Statement', 'Study Objective', '3 Letter of Recommendation', 'Resume/CV'],
                    'commitment' => 'Kembali ke Indonesia selama minimal 2 tahun setelah studi',
                ],
                'typical_deadline' => 'Februari setiap tahun (untuk intake tahun berikutnya)',
                'official_url' => 'https://www.aminef.or.id',
                'is_active' => true,
            ],
            [
                'name' => 'Erasmus+ Joint Masters',
                'country' => 'Eropa (Multi-negara)',
                'education_level' => 'S2',
                'program_type' => 'dual_degree',
                'requirements_summary' => 'Program S2 joint degree dari Uni Eropa dengan studi di minimal 2 negara Eropa. Beasiswa penuh mencakup biaya kuliah, tunjangan hidup, dan biaya travel. Lulusan mendapat dual atau multiple degree dari universitas mitra.',
                'structured_requirements' => [
                    'gpa_min' => 3.0,
                    'language' => 'IELTS 6.5 / TOEFL iBT 90',
                    'documents' => ['Bachelor Degree', 'Transcript', 'Motivation Letter', 'CV Europass', '2 Reference Letters'],
                    'study_mobility' => 'Wajib studi di minimal 2 negara Eropa',
                ],
                'typical_deadline' => 'Oktober - Januari setiap tahun',
                'official_url' => 'https://erasmus-plus.ec.europa.eu',
                'is_active' => true,
            ],
            [
                'name' => 'Chevening Scholarship',
                'country' => 'Inggris (UK)',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa unggulan dari pemerintah Inggris (Foreign Office) untuk S2 di universitas UK. Fokus pada calon pemimpin masa depan. Mencakup biaya kuliah penuh, tunjangan bulanan, tiket pesawat, dan biaya pengajuan visa.',
                'structured_requirements' => [
                    'work_experience_min_years' => 2,
                    'language' => 'IELTS 6.5 (no band below 5.5)',
                    'documents' => ['Personal Essay (Leadership, Networking, Studying in UK, Career Plan)', 'CV', '2 References', 'University Offer (conditional)'],
                    'commitment' => 'Kembali ke Indonesia minimal 2 tahun setelah lulus',
                ],
                'typical_deadline' => 'September - November setiap tahun',
                'official_url' => 'https://www.chevening.org',
                'is_active' => true,
            ],
            [
                'name' => 'DAAD Scholarship',
                'country' => 'Jerman',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa dari Deutscher Akademischer Austauschdienst (DAAD) untuk studi S2/S3 di universitas Jerman. Mencakup tunjangan bulanan, asuransi kesehatan, biaya travel, dan tunjangan studi. Tersedia berbagai program: Development-Related, Research, dan EPOS.',
                'structured_requirements' => [
                    'gpa_min' => 3.0,
                    'work_experience_min_years' => 2,
                    'language' => 'IELTS 6.5 atau German B2 (tergantung program)',
                    'documents' => ['Motivation Letter', 'Research Proposal (untuk Research)', 'CV', 'Recommendation Letters', 'Work Certificate'],
                ],
                'typical_deadline' => 'Agustus - Oktober setiap tahun',
                'official_url' => 'https://www.daad.id',
                'is_active' => true,
            ],
            [
                'name' => 'Global Korea Scholarship (GKS)',
                'country' => 'Korea Selatan',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'Beasiswa dari pemerintah Korea Selatan untuk studi S1/S2/S3 di universitas Korea. Mencakup biaya kuliah penuh, tunjangan hidup, kursus bahasa Korea, asuransi, dan tiket pesawat. Kandidat akan mengikuti kursus bahasa Korea selama 1 tahun sebelum studi formal.',
                'structured_requirements' => [
                    'age_max' => 40,
                    'gpa_min' => 3.0,
                    'language' => 'TOPIK level 3 atau English equivalent (IELTS 5.5+ / TOEFL iBT 71+)',
                    'documents' => ['Personal Statement', 'Study Plan', 'Recommendation Letters', 'Medical Assessment'],
                    'korean_language_year' => 'Wajib 1 tahun kursus bahasa Korea sebelum studi',
                ],
                'typical_deadline' => 'Februari - Maret setiap tahun',
                'official_url' => 'https://www.studyinkorea.go.kr',
                'is_active' => true,
            ],
        ];

        foreach ($targets as $target) {
            Target::firstOrCreate(
                ['name' => $target['name']],
                $target
            );
        }
    }
}