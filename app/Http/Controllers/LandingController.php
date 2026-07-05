<?php

namespace App\Http\Controllers;

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

        // Guest: render public landing page
        return view('landing');
    }
}