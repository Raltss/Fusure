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
    
    // Job seeker only routes
    Route::middleware('role:job_seeker')->group(function () {
        Route::get('/job-seeker/dashboard', function (Request $request) {
            return response()->json([
                'message' => 'Job Seeker Dashboard',
                'user' => $request->user()
            ]);
        });
    });
    
    // Employer only routes
    Route::middleware('role:employer')->group(function () {
        Route::get('/employer/dashboard', function (Request $request) {
            return response()->json([
                'message' => 'Employer Dashboard',
                'user' => $request->user()
            ]);
        });
    });
    
    // Routes accessible by both roles
    Route::middleware('role:job_seeker,employer')->group(function () {
        Route::get('/profile', function (Request $request) {
            return response()->json([
                'message' => 'User Profile',
                'user' => $request->user()
            ]);
        });
    });
});