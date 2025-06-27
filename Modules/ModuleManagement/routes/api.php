<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleManagement\Http\Controllers\ModuleManagementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('modulemanagements', ModuleManagementController::class)->names('modulemanagement');
});
