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


// ============================================================
// LANDING PAGE
// ============================================================

Route::get('/', [DashboardController::class, 'index'])->name('home');


// ============================================================
// AUTH ROUTES
// ============================================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');



// ============================================================
// TRACKS
// ============================================================

Route::redirect('/tracks_create', '/tracks/create', 301);

Route::middleware(['auth'])
    ->prefix('tracks')
    ->name('tracks.')
    ->group(function () {

        Route::get('search/json', [TrackController::class, 'search'])->name('search');

        Route::get('bulk-import', [TrackController::class, 'bulkImport'])->name('bulk.import');
        Route::post('bulk-import', [TrackController::class, 'bulkImportStore'])->name('bulk.import.store');

        Route::patch('{track}/publish', [TrackController::class, 'togglePublish'])->name('publish');

        Route::resource('/', TrackController::class)->parameters(['' => 'track']);

});



// ============================================================
// GENRES
// ============================================================

Route::middleware(['auth'])->group(function () {
    Route::resource('genres', GenreController::class);
});



// ============================================================
// PROFILE
// ============================================================

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::match(['put','patch'], '/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

});



// ============================================================
// FAQ SYSTEM
// ============================================================

// Public FAQ
Route::get('/help/faq', [FaqController::class, 'public'])
    ->name('faq.public');

// Admin FAQ CRUD
Route::middleware(['auth','can:admin-only'])->group(function () {

    Route::resource('faqs', FaqController::class);

    Route::patch('faqs/{faq}/toggle', [FaqController::class,'toggle'])
        ->name('faqs.toggle');

    Route::patch('faqs/{faq}/reorder', [FaqController::class,'reorder'])
        ->name('faqs.reorder');

    Route::resource('faq-categories', FaqCategoryController::class)
        ->except(['show']);

});



// ============================================================
// PRICING
// ============================================================

Route::get('/pricing', [PricingController::class, 'index'])
    ->name('pricing.index');



// ============================================================
// AUTHORS (ADMIN)
// ============================================================

Route::middleware(['auth','can:admin-only'])->group(function () {
    Route::resource('author', AuthorController::class);
});



// ============================================================
// SOUND EFFECTS
// ============================================================

Route::middleware('auth')->group(function () {

    // ---------- BROWSE ----------
    Route::get('/sound-effects/browse', [SoundEffectController::class, 'browse'])
        ->name('sound_effects.browse');

    // ---------- CATEGORY SHORTCUTS ----------
    Route::get('/sound-effects/foley', [SoundEffectController::class, 'foley'])
        ->name('sound_effects.foley');

    Route::get('/sound-effects/soundscape', [SoundEffectController::class, 'soundscape'])
        ->name('sound_effects.soundscape');

    Route::get('/sound-effects/ambience', [SoundEffectController::class, 'ambience'])
        ->name('sound_effects.ambience');

    Route::get('/sound-effects/soundscoring', [SoundEffectController::class, 'soundscoring'])
        ->name('sound_effects.soundscoring');

    // ---------- LANDING ----------
    Route::get('/sound-effects', [SoundEffectController::class, 'index'])
        ->name('sound_effects.index');

    // ---------- LEGACY LIST ----------
    Route::get('/sound-effects/list', [SoundEffectController::class, 'list'])
        ->name('sound_effects.list');

    // ---------- PLAY COUNT ----------
    Route::post('/sound-effects/{sound_effect}/play',
        [SoundEffectController::class, 'incrementPlay'])
        ->name('sound_effects.play');

    // ---------- CRUD ----------
    Route::resource('sound_effects', SoundEffectController::class)
        ->except(['index']);

});



// ============================================================
// SOUND TAXONOMY
// ============================================================

Route::middleware(['auth'])->group(function() {

    Route::resource('sound_categories', SoundCategoryController::class);

    Route::resource('sound_tags', SoundTagController::class);

    Route::resource('sound_licenses', SoundLicenseController::class);

});



// ============================================================
// SOUND SUBCATEGORIES
// ============================================================

Route::middleware(['auth'])->group(function () {

    Route::resource('sound_subcategories', SoundSubcategoryController::class);

    Route::get('sound_subcategories/by-category/{category}',
        [SoundSubcategoryController::class, 'byCategory'])
        ->name('sound_subcategories.byCategory');

});



// ============================================================
// 404 FALLBACK
// ============================================================

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});