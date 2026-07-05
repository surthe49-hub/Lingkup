<?php

namespace App\Http\Controllers;

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
 * Phase 5.6.B will add: hero, navbar, sections, CTA design.
 * Phase 5.6.A delivers: minimal placeholder, routing foundation.
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

        return view('home', [
            'user' => $user,
        ]);
    }
}