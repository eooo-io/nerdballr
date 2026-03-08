<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\ProgressController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => response()->json(['status' => 'ok']));

// Public concept browsing
Route::get('/concepts', [ConceptController::class, 'index']);
Route::get('/concepts/{slug}', [ConceptController::class, 'show']);

// Authenticated user routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('user/bookmarks', BookmarkController::class)
        ->only(['index', 'store', 'destroy']);

    Route::get('user/progress', [ProgressController::class, 'index']);
    Route::post('user/progress', [ProgressController::class, 'store']);
});
