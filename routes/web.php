<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LicenceController;

use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return redirect()->route('licences.index');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::resource('licences', LicenceController::class);

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
