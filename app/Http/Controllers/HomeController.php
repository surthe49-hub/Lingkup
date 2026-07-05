<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
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
 * Update: section "Negara Impianmu" baca dari tabel study_destinations,
 * dan 28 field teks statis (Hero + Journey Card untuk state selain
 * 'active', Section Negara header, Final CTA, Footer) baca dari
 * page_contents — dikelola via Admin > Konten Home.
 *
 * State 'active' SENGAJA tidak baca dari page_contents — isinya
 * dominan data pathway asli user (judul, ringkasan, statistik),
 * bukan konten marketing statis.
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
        $content = PageContent::getForPage('home');

        return view('home', [
            'user' => $user,
            'destinations' => $destinations,
            'content' => $content,
        ]);
    }
}