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
                'file_id' => $this->telegram_file_id,
                'file_path' => $this->telegram_file_path
            ]);
            return asset('images/placeholder.jpg');
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            return asset('images/placeholder.jpg');
        }

        $url = "https://api.telegram.org/file/bot{$botToken}/{$this->telegram_file_path}";
        return $url;
    }
}