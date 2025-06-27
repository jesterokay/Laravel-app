<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;
use Modules\Media\Http\Controllers\TelegramController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::resource('media', MediaController::class)->names('media');
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::post('/media/telegram/webhook', [TelegramController::class, 'webhook'])->name('telegram.webhook');
    Route::get('/media/telegram/send/{mediaId}', [TelegramController::class, 'sendMediaToTelegram'])->name('telegram.send');
});
