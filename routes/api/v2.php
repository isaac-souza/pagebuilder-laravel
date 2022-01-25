<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::get('/auth-check', function() {
    if(is_null(auth()->user()))
    {
        return response()->json(['authenticated' => false], Response::HTTP_OK);
    }
    
    return response()->json(['authenticated' => true], Response::HTTP_OK);
});

