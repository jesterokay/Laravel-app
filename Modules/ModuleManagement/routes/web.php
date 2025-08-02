<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleManagement\Http\Controllers\ModuleManagementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('modulemanagements/upload', [ModuleManagementController::class, 'upload'])->name('modulemanagement.upload');
    Route::post('modulemanagements/{modulemanagement}/toggle', [ModuleManagementController::class, 'toggle'])->name('modulemanagement.toggle');
    Route::resource('modulemanagements', ModuleManagementController::class)->names('modulemanagement');
});