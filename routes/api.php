<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\GuestMigrationController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => response()->json(['status' => 'ok']));

// Public concept browsing
Route::get('/concepts', [ConceptController::class, 'index']);
Route::get('/concepts/{slug}', [ConceptController::class, 'show']);

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// AI endpoints (available to both guests and authenticated users)
Route::post('/ai/query', [AiController::class, 'query']);
Route::get('/ai/session/{key}', [AiController::class, 'session']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('user/bookmarks', BookmarkController::class)
        ->only(['index', 'store', 'destroy']);

    Route::get('user/progress', [ProgressController::class, 'index']);
    Route::post('user/progress', [ProgressController::class, 'store']);

    Route::post('user/migrate-guest', [GuestMigrationController::class, 'migrate']);
});
