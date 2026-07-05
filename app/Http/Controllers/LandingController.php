<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * LandingController
 *
 * Sprint 5.6.A: Route & Navigation Refactor
 *
 * Handles the public landing page at root URL ("/").
 *
 * Behavior:
 * - Guest users: Render landing page (marketing-style)
 * - Authenticated users: Smart redirect to /home (user landing)
 * - Admin users: Smart redirect to /admin/dashboard
 *
 * Update: konten teks (hero, cards, dll) sekarang dikelola lewat
 * Admin > Page Content (tabel `page_contents`), bukan hardcoded
 * lagi di blade. Lihat PageContent::getForPage().
 *
 * @see HomeController for authenticated user landing
 */
class LandingController extends Controller
{
    /**
     * Display the public landing page or redirect authenticated users.
     */
    public function index(): View|RedirectResponse
    {
        // Smart redirect for authenticated users
        if (auth()->check()) {
            $user = auth()->user();

            // Admin goes straight to admin dashboard
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            // Regular user goes to user landing
            return redirect()->route('home');
        }

        // Guest: render public landing page dengan konten dari database
        $content = PageContent::getForPage('landing');

        return view('landing', compact('content'));
    }
}