<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\PostController;

// Resource routes for UserAccountController (CRUD operations)
Route::apiResource('user-accounts', UserAccountController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

// Login route outside middleware group for open access
Route::post('/login', [UserAccountController::class, 'login']);

// Middleware-protected routes
Route::middleware('firebase.auth')->group(function () {
    Route::get('/user-details', [UserAccountController::class, 'getUserDetails']);
    Route::patch('/update-current-user', [UserAccountController::class, 'updateCurrentUserDetails']);
    Route::post('/posts', [PostController::class, 'store']);
});