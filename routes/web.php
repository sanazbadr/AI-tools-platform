<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChatbotController,
    LoginController,
    GoogleAuthController,
    DallEController,
    TTSController,
    STTController,
    DashboardController,
    ArcheoController
};

/*
|--------------------------------------------------------------------------
| Public Website Routes (Marketing / Landing)
|--------------------------------------------------------------------------
*/

// Main domain landing
Route::get('/', fn () => view('welcome'))->name('home');

// Domain-based marketing site
Route::domain('archeo.ai')->group(function () {
    Route::get('/', [ArcheoController::class, 'index'])->name('archeo.home');
    Route::get('/about', [ArcheoController::class, 'about'])->name('archeo.about');
    Route::get('/services', [ArcheoController::class, 'services'])->name('archeo.services');
    Route::get('/contact', [ArcheoController::class, 'contact'])->name('archeo.contact');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('check.session')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Social Auth (Google)
|--------------------------------------------------------------------------
*/

Route::prefix('auth/google')->name('google.')->group(function () {
    Route::match(['get', 'post'], '/', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('login');

    Route::get('/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('callback');

    Route::post('/logout', [GoogleAuthController::class, 'logout'])
        ->middleware('check.session')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Protected App Routes (Dashboard / SaaS UI)
|--------------------------------------------------------------------------
*/

Route::middleware(['check.session'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::view('/upgrade-plan', 'upgrade-plan')->name('upgrade-plan');

    /*
    |--------------------------------------------------------------------------
    | UI Pages (Blade Views)
    |--------------------------------------------------------------------------
    */
    Route::prefix('app')->name('app.')->group(function () {
        Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
        Route::get('/dalle', [DallEController::class, 'index'])->name('dalle');
        Route::get('/tts', [TTSController::class, 'index'])->name('tts');
        Route::get('/stt', [STTController::class, 'sttPage'])->name('stt');
    });
});
