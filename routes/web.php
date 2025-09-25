<?php
use App\Http\Controllers\TrackController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
// Route::get('/tracks', function () {
//     return view('tracks/index');
// });


Route::resource('tracks', TrackController::class);