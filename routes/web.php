<?php

use App\Http\Controllers\Admin\ReportManagementController;
use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\LandingPageController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['admin_or_redirect'])->group(function () {

    // Dashboard with statistics
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });

    // ===========================
    // Site Content Management
    // ===========================
    Route::prefix('admin/site-content')->name('admin.site-content.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SiteContentController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\SiteContentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\SiteContentController::class, 'update'])->name('update');
        Route::post('/{id}/remove-image', [\App\Http\Controllers\Admin\SiteContentController::class, 'removeImage'])->name('remove-image');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\SiteContentController::class, 'destroy'])->name('destroy');
    });

    // ===========================
    // Report Management (Admin)
    // ===========================
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [ReportManagementController::class, 'index'])->name('index');
        Route::get('/approved', [ReportManagementController::class, 'approved'])->name('approved');
        Route::get('/archive', [ReportManagementController::class, 'archive'])->name('archive');
        Route::get('/{report}', [ReportManagementController::class, 'show'])->name('show');
        Route::post('/{report}/approve', [ReportManagementController::class, 'approve'])->name('approve');
        Route::post('/{report}/reject', [ReportManagementController::class, 'reject'])->name('reject');
        Route::delete('/{report}', [ReportManagementController::class, 'destroy'])->name('destroy');
    });

    // ===========================
    // Logged Users (Admin)
    // ===========================
    Route::get('admin/logged-users', [\App\Http\Controllers\Admin\LoggedUsersController::class, 'index'])->name('admin.logged-users.index');
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';

require __DIR__ . '/frontend-routes.php';
