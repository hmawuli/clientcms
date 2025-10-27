<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // ✅ Add this line
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\PageController;
use App\Http\Controllers\Client\AnalyticsController;
use App\Http\Controllers\Client\ThemeController;
use App\Http\Controllers\PublicPageController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Public page display
Route::get('/page/{slug}', [PublicPageController::class, 'show'])
    ->name('public.page');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Pages Management
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/create', [PageController::class, 'create'])->name('create');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::get('/{page}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{page}', [PageController::class, 'update'])->name('update');
        Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');

        // Page actions
        Route::post('/{page}/toggle-publish', [PageController::class, 'togglePublish'])->name('toggle-publish');
        Route::post('/{page}/upload-image', [PageController::class, 'uploadImage'])->name('upload-image');
        Route::post('/{page}/duplicate', [PageController::class, 'duplicate'])->name('duplicate');
        Route::post('/{page}/restore/{version}', [PageController::class, 'restoreVersion'])->name('restore-version');
    });

    // Theme Management
    Route::prefix('themes')->name('themes.')->group(function () {
        Route::get('/', [ThemeController::class, 'index'])->name('index');
        Route::post('/{page}/apply', [ThemeController::class, 'apply'])->name('apply');
        Route::post('/{page}/custom-colors', [ThemeController::class, 'applyCustomColors'])->name('custom-colors');
        Route::get('/{theme}/preview', [ThemeController::class, 'preview'])->name('preview');
    });

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/{page}', [AnalyticsController::class, 'show'])->name('show');
        Route::get('/{page}/export', [AnalyticsController::class, 'export'])->name('export');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Optional)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Add more admin routes as needed
});

/*
|--------------------------------------------------------------------------
| Redirect Root to Login or Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) { //  Use Auth::check() instead of auth()->check()
        return redirect()->route('client.dashboard');
    }
    return redirect()->route('login');
});
