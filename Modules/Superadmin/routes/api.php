<?php

use Illuminate\Support\Facades\Route;
use Modules\Superadmin\Http\Controllers\SuperadminController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('superadmins', SuperadminController::class)->names('superadmin');
});
