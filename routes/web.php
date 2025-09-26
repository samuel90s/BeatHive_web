<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;

// Opsi A: pakai landing page bawaan (views/index.blade.php)
Route::get('/', function () {
    return view('index');
});

// Resource lengkap: index, create, store, show, edit, update, destroy
Route::resource('tracks', TrackController::class);

/*
|----------------------------------------------------------------------
| CATATAN
|----------------------------------------------------------------------
| - Route::resource('tracks', ...) sudah otomatis membuat:
|   tracks.index, tracks.create, tracks.store, tracks.show,
|   tracks.edit, tracks.update, tracks.destroy
| - Jadi baris di bawah TIDAK diperlukan dan sebaiknya dihapus:
|     Route::get('/tracks/create', [TrackController::class, 'create'])->name('tracks.create');
|
| Opsi B (kalau mau homepage langsung ke daftar tracks), ganti route '/' di atas menjadi:
|   Route::redirect('/', '/tracks');
*/
