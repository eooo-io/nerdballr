<?php

use Illuminate\Support\Facades\Route;

// Public API routes
Route::get('/ping', fn () => response()->json(['status' => 'ok']));
