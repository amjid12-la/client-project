<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'landingPage'])->name('home');

// Static Pages
Route::get('/privacy-policy', [LandingPageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-conditions', [LandingPageController::class, 'termsConditions'])->name('terms-conditions');

// Public Report Routes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Protected Report Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/submit-report', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/submit-report', [ReportController::class, 'store'])->name('reports.store');
});
