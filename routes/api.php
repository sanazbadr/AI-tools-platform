<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TTSController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// TTS API Routes - Add web middleware to maintain session
Route::middleware(['web', 'auth.check'])->group(function () {
    Route::post('/tts/convert', [TTSController::class, 'convert']);
    Route::get('/tts/voices', [TTSController::class, 'getVoices']);
});
