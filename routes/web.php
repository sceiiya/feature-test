<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/vid', 'VideoController@index');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Route::get('/generate-pdf', 'PDFController@generatePDF');

//? PDF ROutes
Route::get('/pdf/ping', [PDFController::class, 'ping']);
Route::post('/pdf/upload', [PDFController::class, 'upload'])->name('upload-pdf');
Route::get('/pdf/file-upload', [PDFController::class, 'uploadView']);
Route::get('/pdf/stamp', [PDFController::class, 'stamp']);

Route::get('/generate-pdf', [PDFController::class, 'generatePDF']);

require __DIR__.'/auth.php';
