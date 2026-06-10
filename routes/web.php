<?php

use App\Http\Controllers\User\ProfileAssessmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\TargetController;
use Illuminate\Support\Facades\Route;

// ============================================
// Public Routes
// ============================================
Route::get('/', function () {
    return view('welcome');
});

// ============================================
// Authenticated User Routes
// ============================================
Route::middleware(['auth'])->group(function () {
    
    // User Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile routes dari Breeze
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
    });

require __DIR__.'/auth.php';