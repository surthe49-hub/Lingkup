<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\View\View;

/**
 * AboutController
 *
 * Sprint 5.6.D: About Us page
 * Handles the /about route — describes LINGKUP, mission, team.
 *
 * Update: seluruh konten teks (39 field) sekarang dikelola lewat
 * Admin > Konten About (tabel page_contents), bukan hardcoded lagi
 * di blade.
 */
class AboutController extends Controller
{
    /**
     * Display the About Us page.
     */
    public function index(): View
    {
        $content = PageContent::getForPage('about');

        return view('about', compact('content'));
    }
}