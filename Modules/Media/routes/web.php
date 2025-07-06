<?php
use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;

Route::group(['prefix' => 'media', 'as' => 'media.'], function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::get('/create', [MediaController::class, 'create'])->name('create');
    Route::post('/', [MediaController::class, 'store'])->name('store');
    Route::get('/{media}', [MediaController::class, 'show'])->name('show');
    Route::get('/{media}/edit', [MediaController::class, 'edit'])->name('edit');
    Route::put('/{media}', [MediaController::class, 'update'])->name('update');
    Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
});
