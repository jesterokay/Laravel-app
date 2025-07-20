<?php

use Illuminate\Support\Facades\Route;
use Modules\Superadmin\Http\Controllers\SuperadminController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('superadmins', SuperadminController::class)->names('superadmin');
});
