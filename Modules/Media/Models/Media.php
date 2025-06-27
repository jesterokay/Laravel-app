<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $fillable = ['title', 'description', 'telegram_file_id', 'media_type', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getMediaUrlAttribute()
    {
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
        $response = \Illuminate\Support\Facades\Http::get("https://api.telegram.org/bot{$botToken}/getFile", ['file_id' => $this->telegram_file_id]);
        $filePath = $response->json()['result']['file_path'];
        return "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
    }
}