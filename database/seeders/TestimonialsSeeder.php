<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialsSeeder extends Seeder
{
    /**
     * Migrasi 6 testimonial demo yang sebelumnya hardcoded
     * di ReviewsController.php ke database.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Andini',
                'role' => 'Penerima Beasiswa AAS 2025',
                'avatar_color' => 'primary',
                'rating' => 5,
                'message' => 'LINGKUP membantu saya menyusun roadmap persiapan yang lebih terstruktur. AI-nya memahami konteks profil saya dan memberikan task yang relevan dengan timeline yang realistis.',
                'display_order' => 1,
            ],
            [
                'name' => 'Budi Santoso',
                'role' => 'Calon Penerima MEXT',
                'avatar_color' => 'peach',
                'rating' => 5,
                'message' => 'Sebagai mahasiswa di kota kecil, akses ke mentor beasiswa terbatas. LINGKUP memberikan panduan AI yang setara dengan konsultan beasiswa premium — dan gratis.',
                'display_order' => 2,
            ],
            [
                'name' => 'Citra Maharani',
                'role' => 'Awardee Chevening 2024',
                'avatar_color' => 'teal',
                'rating' => 5,
                'message' => 'Yang paling saya suka adalah pathway dibagi per fase. Saya bisa fokus ke satu tahap dulu tanpa overwhelmed dengan semua persiapan sekaligus.',
                'display_order' => 3,
            ],
            [
                'name' => 'Dewi Pratiwi',
                'role' => 'Persiapan Erasmus+',
                'avatar_color' => 'green',
                'rating' => 5,
                'message' => 'Interface dalam Bahasa Indonesia membuat saya lebih nyaman. AI-nya juga memberikan rekomendasi yang spesifik untuk konteks mahasiswa Indonesia.',
                'display_order' => 4,
            ],
            [
                'name' => 'Eko Wijaya',
                'role' => 'Mahasiswa S1 Sistem Informasi',
                'avatar_color' => 'pink',
                'rating' => 4,
                'message' => 'Cocok untuk yang masih bingung mulai dari mana. Profile assessment-nya cukup detail dan output pathway-nya actionable. Tinggal eksekusi.',
                'display_order' => 5,
            ],
            [
                'name' => 'Farah Nadia',
                'role' => 'Calon Awardee LPDP',
                'avatar_color' => 'primary',
                'rating' => 5,
                'message' => 'Saya sempat skeptis dengan AI generator, tapi LINGKUP memberikan output yang tidak generik. Task-nya benar-benar disesuaikan dengan jurusan dan target saya.',
                'display_order' => 6,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
