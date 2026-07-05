<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\View\View;

/**
 * ReviewsController
 *
 * Sprint 5.6.D: Reviews & testimonials page
 * Handles the /reviews route.
 *
 * Update: testimonials sekarang dikelola lewat Admin > Testimonials
 * (tabel `testimonials`), bukan lagi array hardcoded di controller ini.
 * Hanya testimonial dengan is_active=true yang ditampilkan, diurutkan
 * berdasarkan display_order.
 */
class ReviewsController extends Controller
{
    /**
     * Display the Reviews page.
     */
    public function index(): View
    {
        $testimonials = Testimonial::active()->ordered()->get();

        return view('reviews', compact('testimonials'));
    }
}