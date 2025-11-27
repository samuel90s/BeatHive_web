<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FaqCategoryController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\SoundEffectController;
use App\Http\Controllers\SoundCategoryController;
use App\Http\Controllers\SoundTagController;
use App\Http\Controllers\SoundLicenseController;
use App\Http\Controllers\SoundSubcategoryController;




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
    Route::match(['put','patch'], '/profile', [ProfileController::class, 'update'])
        ->name('profile.update');  // ⬅️ kini terima PUT & PATCH
    // lainnya tetap:
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
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



//Payment
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');

Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [PricingController::class, 'checkout'])->name('checkout');
    Route::get('/payment/finish', [PricingController::class, 'paymentFinish'])->name('payment.finish');
    Route::get('/payment/success', [PricingController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/pending', [PricingController::class, 'paymentPending'])->name('payment.pending');
    Route::get('/payment/error', [PricingController::class, 'paymentError'])->name('payment.error');
});

// Webhook route - tanpa CSRF protection
Route::post('/payment/notification', [PricingController::class, 'paymentNotification']);





Route::middleware(['auth','can:admin-only'])->group(function () {
    Route::resource('author', AuthorController::class); // author.index -> AuthorController@index
});



Route::middleware('auth')->group(function () {
    Route::get('/sound-effects/browse', [SoundEffectController::class, 'browse'])
        ->name('sound_effects.browse');

    // kalau grid kategori kamu taruh di index:
    Route::get('/sound-effects', [SoundEffectController::class, 'index'])
        ->name('sound_effects.index');

    Route::get('/sound-effects/list', [SoundEffectController::class, 'list'])
        ->name('sound_effects.list');

    Route::resource('sound_effects', SoundEffectController::class)->except(['index']);
});

Route::middleware(['auth'])->group(function() {
    Route::resource('sound_categories', SoundCategoryController::class);
    Route::resource('sound_tags', SoundTagController::class);
    Route::resource('sound_licenses', SoundLicenseController::class);
});

Route::middleware(['auth'])->group(function () {
    // CRUD utama
    Route::resource('sound_subcategories', SoundSubcategoryController::class);
    // route tambahan untuk filter subcategory by category (digunakan di form SoundEffect)
    Route::get('sound_subcategories/by-category/{category}', 
        [SoundSubcategoryController::class, 'byCategory']
    )->name('sound_subcategories.byCategory');

});

