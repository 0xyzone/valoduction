<?php

use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\OverlayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/changelog', ChangelogController::class)->name('changelog');

Route::prefix('overlays/matches/{gameMatch}')->name('overlays.')->group(function () {
    Route::get('/veto', [OverlayController::class, 'veto'])->name('veto');
    Route::get('/post-match', [OverlayController::class, 'postMatch'])->name('post-match');
});
