<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Modules\Jester\Http\Controllers\ChatController;
use Modules\Jester\Http\Controllers\JesterController;
use Modules\Jester\Http\Controllers\DeepSeekController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('jesters', JesterController::class)->names('jester');
    Route::prefix('jester')->name('jester.')->group(function () {
        Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('chat/send', [ChatController::class, 'send'])->name('chat.send');
        Route::post('chat/new', [ChatController::class, 'new'])->name('chat.new');
        Route::post('chat/rename', [ChatController::class, 'rename'])->name('chat.rename');
        Route::post('chat/delete', [ChatController::class, 'delete'])->name('chat.delete');
        Route::post('chat/edit', [ChatController::class, 'edit'])->name('chat.edit');
    });
    Route::prefix('jester')->group(function () {
        Route::match(['get', 'post'], '/deepseek', [DeepSeekController::class, 'chat'])->name('jester.deepseek.chat_page');
        Route::post('/deepseek/clear', [DeepSeekController::class, 'clearConversation'])->name('clear_chat');
    });
});
