<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LandingPageDraftController;

Route::middleware('delay')->group(function() {

    Route::get('/auth/check', [AuthController::class, 'check']);
    Route::get('/auth/account', [AuthController::class, 'account']);

    Route::put('landing-pages/{uuid}/draft', [LandingPageDraftController::class, 'update']);

    Route::get('landing-pages',             [LandingPageController::class, 'index']);
    Route::get('landing-pages/{uuid}',      [LandingPageController::class, 'show']);
    Route::post('landing-pages',            [LandingPageController::class, 'store']);
    Route::put('landing-pages/{uuid}',      [LandingPageController::class, 'update']);
    Route::delete('landing-pages/{uuid}',   [LandingPageController::class, 'destroy']);

});
