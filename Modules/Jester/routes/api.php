<?php

use Illuminate\Support\Facades\Route;
use Modules\Jester\Http\Controllers\JesterController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('jesters', JesterController::class)->names('jester');
});
