<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FaqCategoryController;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =======================
// Landing page
// =======================
Route::get('/', [DashboardController::class, 'index'])->name('home');

// =======================
// Auth (Login, Register, Forgot, Reset, Logout)
// =======================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Forgot password
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Reset password
    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =======================
// Tracks group (custom endpoints + CRUD)
// =======================
Route::redirect('/tracks_create', '/tracks/create', 301);

Route::middleware(['auth'])->prefix('tracks')->name('tracks.')->group(function () {
    // Quick search JSON (untuk Select2/autocomplete)
    Route::get('search/json', [TrackController::class, 'search'])->name('search');

    // Bulk import (halaman + submit) → hanya admin
    Route::get('bulk-import', [TrackController::class, 'bulkImport'])->name('bulk.import');
    Route::post('bulk-import', [TrackController::class, 'bulkImportStore'])->name('bulk.import.store');

    // Toggle publish (aksi cepat) → hanya admin
    Route::patch('{track}/publish', [TrackController::class, 'togglePublish'])->name('publish');

    // Resource utama (index, create, store, show, edit, update, destroy)
    Route::resource('/', TrackController::class)->parameters(['' => 'track']);
});

// =======================
// Fallback 404
// =======================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});


Route::middleware(['auth'])->group(function () {
    Route::resource('genres', GenreController::class);
});

// =======================



// Profile


// =======================


Route::middleware('auth')->group(function () {


    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');


    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');


    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});


Route::middleware(['auth'])->group(function () {
Route::resource('faqs', FaqController::class);
Route::patch('faqs/{faq}/toggle', [FaqController::class,'toggle'])->name('faqs.toggle');
Route::patch('faqs/{faq}/reorder', [FaqController::class,'reorder'])->name('faqs.reorder');


Route::resource('faq-categories', FaqCategoryController::class)->except(['show']);
});


// route for public help center

// Publik: semua boleh
Route::get('/help/faq', [FaqController::class, 'public'])->name('faq.public');

// CRUD: hanya admin
Route::middleware(['auth','can:admin-only'])->group(function () {
    Route::resource('faqs', FaqController::class);
    Route::patch('faqs/{faq}/toggle', [FaqController::class,'toggle'])->name('faqs.toggle');
    Route::patch('faqs/{faq}/reorder', [FaqController::class,'reorder'])->name('faqs.reorder');
    Route::resource('faq-categories', FaqCategoryController::class)->except(['show']);
});