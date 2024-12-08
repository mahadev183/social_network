<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAccountController;

// Resource routes for UserAccountController (CRUD operations)
Route::apiResource('user-accounts', UserAccountController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

// Login route outside middleware group for open access
Route::post('/login', [UserAccountController::class, 'login']);
// Route::middleware('firebase.auth')->get('/user-details', [UserController::class, 'getUserDetails']);

// Middleware-protected routes
Route::middleware('firebase.auth')->group(function () {
    Route::get('/user-details', [UserAccountController::class, 'getUserDetails']);
    Route::patch('/update-current-user', [UserAccountController::class, 'updateCurrentUserDetails']);
});