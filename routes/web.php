<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TikTokController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tiktok/redirect', [TikTokController::class, 'redirectToTikTok'])->name('tiktok.redirect');
Route::get('/tiktok/callback', [TikTokController::class, 'handleTikTokCallback'])->name('tiktok.callback');
Route::get('/tiktok/upload', [TikTokController::class, 'showUploadForm'])->name('upload.form');
Route::post('/tiktok/upload', [TikTokController::class, 'uploadVideo'])->name('upload.video');