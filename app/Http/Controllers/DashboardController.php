<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard untuk user biasa.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile;
        $activeTarget = $user->userTarget?->target;

        return view('user.dashboard', [
            'user' => $user,
            'profile' => $profile,
            'hasProfile' => $profile !== null,
            'isProfileComplete' => $profile?->isComplete() ?? false,
            'completionPercentage' => $profile?->completion_percentage ?? 0,
            'activeTarget' => $activeTarget,
        ]);
    }
}