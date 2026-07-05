<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * ReviewsController
 *
 * Sprint 5.6.D: Reviews & testimonials page
 * Handles the /reviews route — placeholder testimonials for demo.
 *
 * Note: Reviews shown are demo data, not real user testimonials.
 */
class ReviewsController extends Controller
{
    /**
     * Display the Reviews page.
     */
    public function index(): View
    {
        // Demo testimonials (will be replaced with real reviews when available)
        $testimonials = [
            [
                'name' => 'Andini',
                'role' => 'Penerima Beasiswa AAS 2025',
                'avatar_color' => 'primary',
                'rating' => 5,
                'message' => 'LINGKUP membantu saya menyusun roadmap persiapan yang lebih terstruktur. AI-nya memahami konteks profil saya dan memberikan task yang relevan dengan timeline yang realistis.',
            ],
            [
                'name' => 'Budi Santoso',
                'role' => 'Calon Penerima MEXT',
                'avatar_color' => 'peach',
                'rating' => 5,
                'message' => 'Sebagai mahasiswa di kota kecil, akses ke mentor beasiswa terbatas. LINGKUP memberikan panduan AI yang setara dengan konsultan beasiswa premium — dan gratis.',
            ],
            [
                'name' => 'Citra Maharani',
                'role' => 'Awardee Chevening 2024',
                'avatar_color' => 'teal',
                'rating' => 5,
                'message' => 'Yang paling saya suka adalah pathway dibagi per fase. Saya bisa fokus ke satu tahap dulu tanpa overwhelmed dengan semua persiapan sekaligus.',
            ],
            [
                'name' => 'Dewi Pratiwi',
                'role' => 'Persiapan Erasmus+',
                'avatar_color' => 'green',
                'rating' => 5,
                'message' => 'Interface dalam Bahasa Indonesia membuat saya lebih nyaman. AI-nya juga memberikan rekomendasi yang spesifik untuk konteks mahasiswa Indonesia.',
            ],
            [
                'name' => 'Eko Wijaya',
                'role' => 'Mahasiswa S1 Sistem Informasi',
                'avatar_color' => 'pink',
                'rating' => 4,
                'message' => 'Cocok untuk yang masih bingung mulai dari mana. Profile assessment-nya cukup detail dan output pathway-nya actionable. Tinggal eksekusi.',
            ],
            [
                'name' => 'Farah Nadia',
                'role' => 'Calon Awardee LPDP',
                'avatar_color' => 'primary',
                'rating' => 5,
                'message' => 'Saya sempat skeptis dengan AI generator, tapi LINGKUP memberikan output yang tidak generik. Task-nya benar-benar disesuaikan dengan jurusan dan target saya.',
            ],
        ];

        return view('reviews', compact('testimonials'));
    }
}