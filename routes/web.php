<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// =======================
// Landing page
// =======================
Route::get('/', function () {
    return view('index');
})->name('home');

// =======================
// Legacy redirect (optional)
// =======================
Route::redirect('/tracks_create', '/tracks/create', 301);

// =======================
// Tracks group (custom endpoints + CRUD)
// =======================
Route::prefix('tracks')->name('tracks.')->group(function () {
    // Quick search JSON (untuk Select2/autocomplete)
    Route::get('search/json', [TrackController::class, 'search'])->name('search');

    // Bulk import (halaman + submit)
    Route::get('bulk-import', [TrackController::class, 'bulkImport'])->name('bulk.import');
    Route::post('bulk-import', [TrackController::class, 'bulkImportStore'])->name('bulk.import.store');

    // Toggle publish (aksi cepat)
    Route::patch('{track}/publish', [TrackController::class, 'togglePublish'])->name('publish');
});

// =======================
// Resource utama (CRUD)
// =======================
// ini sudah otomatis menyediakan:
// GET /tracks -> tracks.index
// GET /tracks/create -> tracks.create
// POST /tracks -> tracks.store
// GET /tracks/{track} -> tracks.show
// GET /tracks/{track}/edit -> tracks.edit
// PUT/PATCH /tracks/{track} -> tracks.update
// DELETE /tracks/{track} -> tracks.destroy
Route::resource('tracks', TrackController::class);

// =======================
// Auth placeholder (biar header nggak error)
// =======================
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// =======================
// Fallback 404
// =======================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
