<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\User\TargetController;
use App\Http\Controllers\User\PathwayController;
use App\Http\Controllers\User\ProfileAssessmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


// ============================================
// Public Routes
// ============================================
// Sprint 5.6.A: Landing page publik (guest) + smart redirect (authenticated)
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ============================================
// Authenticated User Routes
// ============================================
Route::middleware(['auth'])->group(function () {

    // Sprint 5.6.A: User landing page (post-login welcome area)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Sprint 5.6.D: About Us page
    Route::get('/about', [AboutController::class, 'index'])->name('about');

    // Sprint 5.6.D: Reviews & testimonials page
    Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews');

    // User Dashboard (working area)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile Routes (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================
    // Profile Assessment
    // ============================================
    Route::prefix('profile-assessment')
        ->name('profile-assessment.')
        ->controller(ProfileAssessmentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/edit', 'edit')->name('edit');
            Route::post('/', 'store')->name('store');
            Route::put('/', 'update')->name('update');
            Route::get('/review', 'show')->name('show');
        });

    // ============================================
    // Target Selection (Sprint 3)
    // ============================================
    Route::prefix('targets')
        ->name('target.')
        ->controller(TargetController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{target}', 'show')->name('show');
            Route::post('/{target}/select', 'select')->name('select');
        });

    // ============================================
    // Pathway Management (Sprint 4 & 5)
    // ============================================
    Route::prefix('pathway')
        ->name('user.pathway.')
        ->controller(PathwayController::class)
        ->group(function () {
            // Phase 4: Landing page (redirect ke active pathway / empty state)
            Route::get('/', 'index')->name('index');

            // Phase 3A: Generation endpoint (AJAX)
            Route::post('/generate', 'generate')->name('generate');

            // Phase 5.2: Regenerate existing pathway (AJAX)
            Route::post('/regenerate', 'regenerate')->name('regenerate');

            // Phase 3A + Phase 4: Detail view (dual-mode JSON/View)
            Route::get('/{pathway}', 'show')->name('show');

           

        });

        // ROUTE PROGRESS — taruh DI SINI, di luar group pathway, tapi masih dalam middleware auth yang sama
            Route::get('/progress', [\App\Http\Controllers\User\ProgressController::class, 'index'])
                ->name('user.progress.index');

            Route::patch('/progress/tasks/{task}', [\App\Http\Controllers\User\ProgressController::class, 'update'])
                ->name('user.progress.update');
});

// ============================================
// Admin Routes
// ============================================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

    // Route admin lainnya akan ditambahkan di sprint berikutnya
    Route::prefix('users')
    ->name('users.')
    ->controller(AdminUserController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/{user}/suspend', 'suspend')->name('suspend');
        Route::patch('/{user}/activate', 'activate')->name('activate');
        Route::patch('/{user}/role', 'updateRole')->name('update-role');
        Route::delete('/{user}', 'destroy')->name('destroy');
        Route::patch('/{user}/restore', 'restore')->name('restore')->withTrashed();
    });

    Route::prefix('targets')
    ->name('targets.')
    ->controller(\App\Http\Controllers\Admin\TargetController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{target}/edit', 'edit')->name('edit');
        Route::put('/{target}', 'update')->name('update');
        Route::patch('/{target}/toggle-active', 'toggleActive')->name('toggle-active');
        Route::delete('/{target}', 'destroy')->name('destroy');
        Route::patch('/{target}/restore', 'restore')->name('restore')->withTrashed();
    });

    });

require __DIR__.'/auth.php';