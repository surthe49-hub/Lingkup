<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
{
        $user = $request->user();
        $profile = $user->profile;

        return view(
            'user.dashboard',
            compact('user', 'profile')
        );
}
}