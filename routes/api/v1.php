<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LandingPageDraftController;

Route::middleware(['simulate.network.delay', 'auth:sanctum'])->group(function() {

    Route::get('/auth/check', [AuthController::class, 'check']);
    Route::get('/auth/account', [AuthController::class, 'account']);

    Route::put('landing-pages/{uuid}/draft', [LandingPageDraftController::class, 'update']);

    Route::get('landing-pages',             [LandingPageController::class, 'index'])->name('landing-pages.index');
    Route::get('landing-pages/{uuid}',      [LandingPageController::class, 'show'])->name('landing-pages.show');
    Route::post('landing-pages',            [LandingPageController::class, 'store'])->name('landing-pages.store');
    Route::put('landing-pages/{uuid}',      [LandingPageController::class, 'update'])->name('landing-pages.update');
    Route::delete('landing-pages/{uuid}',   [LandingPageController::class, 'destroy'])->name('landing-pages.destroy');

});
