<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});