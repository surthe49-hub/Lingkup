<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * AboutController
 *
 * Sprint 5.6.D: About Us page
 * Handles the /about route — describes LINGKUP, mission, team.
 */
class AboutController extends Controller
{
    /**
     * Display the About Us page.
     */
    public function index(): View
    {
        return view('about');
    }
}