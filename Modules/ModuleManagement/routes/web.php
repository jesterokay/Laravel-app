<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleManagement\Http\Controllers\ModuleManagementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('modulemanagements/upload', [ModuleManagementController::class, 'upload'])->name('modulemanagement.upload');
    Route::resource('modulemanagements', ModuleManagementController::class)->names('modulemanagement');
});