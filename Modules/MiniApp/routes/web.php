<?php

use Illuminate\Support\Facades\Route;
use Modules\MiniApp\Http\Controllers\MiniAppController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('miniapps', MiniAppController::class)->names('miniapp');
});
