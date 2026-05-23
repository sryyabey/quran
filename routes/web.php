<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Share\NoteShareController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/giris',  [LoginController::class, 'create'])->name('login');
    Route::post('/giris', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/cikis', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole('user')) {
            return redirect()->route('user.dashboard');
        }
        return redirect('/salih');
    }

    return view('welcome');
})->name('home');

Route::middleware(['auth', 'role:user|super_admin'])->group(function (): void {
    Route::view('/dashboard', 'dashboard.user')->name('user.dashboard');
    Route::view('/kuran-okuma', 'quran.read')->name('user.quran-read');
    Route::view('/kuran-metin', 'quran.text')->name('user.quran-text');
    Route::view('/kuran-notlar', 'quran.notes-range')->name('user.quran-notes-range');
    Route::view('/paylasimlarim', 'shares.index')->name('user.shares');
    Route::view('/istatistikler', 'statistics.index')->name('user.statistics');
    Route::view('/ayarlar', 'settings.index')->name('user.settings');
});

Route::get('/dil/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch')
    ->where('locale', 'tr|en');

Route::get('/paylas/notlar/{token}', [NoteShareController::class, 'show'])->name('notes.share.show');
