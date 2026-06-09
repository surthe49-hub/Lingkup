<?php

namespace Database\Seeders;

use App\Models\Target;
use Illuminate\Database\Seeder;

class TargetsSeeder extends Seeder
{
    public function run(): void
    {
        $targets = [
            [
                'name' => 'Global Korea Scholarship (GKS)',
                'country' => 'Korea Selatan',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'IPK minimal 3.0; usia maks 40 tahun; TOPIK level 3 atau setara; sertifikasi bahasa Inggris',
                'typical_deadline' => 'Februari-Maret setiap tahun',
                'official_url' => 'https://www.studyinkorea.go.kr',
                'is_active' => true,
            ],
            [
                'name' => 'LPDP (Lembaga Pengelola Dana Pendidikan)',
                'country' => 'Indonesia/Global',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => 'WNI; IPK minimal 3.0; LoA atau letter of recommendation; sertifikasi bahasa',
                'typical_deadline' => 'Februari dan Juli (2 batch per tahun)',
                'official_url' => 'https://lpdp.kemenkeu.go.id',
                'is_active' => true,
            ],
            [
                'name' => 'Erasmus Mundus Joint Masters',
                'country' => 'Eropa (multi negara)',
                'education_level' => 'S2',
                'program_type' => 'dual_degree',
                'requirements_summary' => 'Gelar S1; IELTS minimal 6.5; motivation letter; LoR',
                'typical_deadline' => 'Oktober-Januari',
                'official_url' => 'https://erasmus-plus.ec.europa.eu',
                'is_active' => true,
            ],
            [
                'name' => 'Chevening Scholarship',
                'country' => 'Inggris',
                'education_level' => 'S2',
                'program_type' => 'scholarship',
                'requirements_summary' => '2 tahun pengalaman kerja; LoA dari universitas UK; IELTS 6.5',
                'typical_deadline' => 'September-November',
                'official_url' => 'https://www.chevening.org',
                'is_active' => true,
            ],
            [
                'name' => 'IISMA (Indonesian International Student Mobility Awards)',
                'country' => 'Multi negara',
                'education_level' => 'Exchange',
                'program_type' => 'exchange',
                'requirements_summary' => 'Mahasiswa aktif S1 semester 4-7; IPK minimal 3.0; IELTS 6.0',
                'typical_deadline' => 'Maret-April',
                'official_url' => 'https://iisma.kemdikbud.go.id',
                'is_active' => true,
            ],
        ];

        foreach ($targets as $target) {
            Target::firstOrCreate(['name' => $target['name']], $target);
        }
    }
}