<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenceController;

use App\Http\Controllers\SettingController;

use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::resource('licences', LicenceController::class);

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
Route::post('settings/test-email', [SettingController::class, 'sendTestEmail'])->name('settings.test_email');

// Webhook for external Cron Job (e.g., cron-job.org)
Route::get('/webhook/trigger-reminders', function (\Illuminate\Http\Request $request) {
    // Basic security: require a secret token in the URL to prevent unauthorized triggers
    $secret = env('CRON_SECRET', 'KCICRahasia2026!');
    if ($request->query('token') !== $secret) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Call the Artisan command directly
    \Illuminate\Support\Facades\Artisan::call('app:send-licence-reminders');
    
    return response()->json([
        'success' => true,
        'message' => 'Reminders triggered successfully',
        'output' => \Illuminate\Support\Facades\Artisan::output()
    ]);
});
