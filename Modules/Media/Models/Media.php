<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'title',
        'description',
        'telegram_file_id',
        'telegram_file_path',
        'telegram_message_id',
        'media_type'
    ];

    public function getTelegramFileUrl()
    {
        if (!$this->telegram_file_id || !$this->telegram_file_path) {
            \Log::warning('Missing telegram_file_id or telegram_file_path for media ID: ' . $this->id, [
                'file_id' => $this->telegram_file_id,
                'file_path' => $this->telegram_file_path
            ]);
            return asset('images/placeholder.jpg');
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            \Log::error('TELEGRAM_BOT_TOKEN not set in .env');
            return asset('images/placeholder.jpg');
        }

        $url = "https://api.telegram.org/file/bot{$botToken}/{$this->telegram_file_path}";
        \Log::info('Generated Telegram file URL', ['url' => $url, 'media_id' => $this->id]);
        return $url;
    }
}