<?php

namespace App\Http\Controllers;

use App\Models\StudyDestination;
use Illuminate\View\View;

/**
 * HomeController
 *
 * Sprint 5.6.A: Route & Navigation Refactor
 *
 * Handles the authenticated user landing page at "/home".
 *
 * Purpose:
 * - Welcome area for logged-in users (separated from working dashboard)
 * - Marketing-style overview accessible after login
 * - User clicks "Masuk ke Dashboard" to enter working area
 *
 * Update: section "Negara Impianmu" sekarang baca dari tabel
 * study_destinations (dikelola via Admin > Study Destinations),
 * bukan hardcoded lagi di blade.
 *
 * @see LandingController for public guest landing
 * @see DashboardController for authenticated working area
 */
class HomeController extends Controller
{
    /**
     * Display the authenticated user landing page.
     */
    public function index(): View
    {
        $user = auth()->user();
        $destinations = StudyDestination::active()->ordered()->get();

        return view('home', [
            'user' => $user,
            'destinations' => $destinations,
        ]);
    }
}