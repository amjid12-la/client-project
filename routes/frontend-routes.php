<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'landingPage'])->name('home');

// Public Report Routes
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Protected Report Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/submit-report', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/submit-report', [ReportController::class, 'store'])->name('reports.store');
});
