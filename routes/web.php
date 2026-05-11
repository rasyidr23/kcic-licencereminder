<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// Authentication Routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Public Webhook (No Auth)
Route::get('/webhook/trigger-reminders', function (\Illuminate\Http\Request $request) {
    $secret = env('CRON_SECRET', 'KCICRahasia2026!');
    if ($request->query('token') !== $secret) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    \Illuminate\Support\Facades\Artisan::call('app:send-licence-reminders');
    return response()->json([
        'success' => true,
        'message' => 'Reminders triggered successfully',
        'output' => \Illuminate\Support\Facades\Artisan::output()
    ]);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'id'])) {
            session()->put('locale', $locale);
        }
        return redirect()->back();
    })->name('lang.switch');

    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('licences/create', [LicenceController::class, 'create'])->name('licences.create');
        Route::post('licences', [LicenceController::class, 'store'])->name('licences.store');
        Route::get('licences/{licence}/edit', [LicenceController::class, 'edit'])->name('licences.edit');
        Route::put('licences/{licence}', [LicenceController::class, 'update'])->name('licences.update');
        Route::delete('licences/{licence}', [LicenceController::class, 'destroy'])->name('licences.destroy');
        
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/test-email', [SettingController::class, 'sendTestEmail'])->name('settings.test_email');
    });

    // Viewer / Admin can see these
    Route::get('licences', [LicenceController::class, 'index'])->name('licences.index');
    Route::get('licences/{licence}', [LicenceController::class, 'show'])->name('licences.show');
});
