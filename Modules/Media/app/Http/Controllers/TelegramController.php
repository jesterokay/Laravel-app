<?php

namespace Modules\Media\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Media\Models\Media;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    private $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
    private $chatId = '1601089836';
    private $apiUrl = 'https://api.telegram.org/bot';

    public function webhook(Request $request)
    {
        $update = $request->all();

        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];

            // Handle photo uploads
            if (isset($message['photo'])) {
                $photo = end($message['photo']); // Get largest photo
                $fileId = $photo['file_id'];

                Media::create([
                    'title' => 'Telegram Photo',
                    'description' => 'Uploaded via Telegram',
                    'telegram_file_id' => $fileId,
                    'media_type' => 'image',
                    'user_id' => 1, // Replace with auth()->id() if authenticated
                ]);

                Http::post("{$this->apiUrl}{$this->botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => 'Image uploaded successfully!'
                ]);
            }

            // Handle video uploads
            if (isset($message['video'])) {
                $video = $message['video'];
                $fileId = $video['file_id'];

                Media::create([
                    'title' => 'Telegram Video',
                    'description' => 'Uploaded via Telegram',
                    'telegram_file_id' => $fileId,
                    'media_type' => 'video',
                    'user_id' => 1, // Replace with auth()->id() if authenticated
                ]);

                Http::post("{$this->apiUrl}{$this->botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => 'Video uploaded successfully!'
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function sendMediaToTelegram($mediaId)
    {
        $media = Media::findOrFail($mediaId);

        if ($media->media_type === 'image') {
            Http::post("{$this->apiUrl}{$this->botToken}/sendPhoto", [
                'chat_id' => $this->chatId,
                'photo' => $media->telegram_file_id,
                'caption' => $media->title,
            ]);
        } elseif ($media->media_type === 'video') {
            Http::post("{$this->apiUrl}{$this->botToken}/sendVideo", [
                'chat_id' => $this->chatId,
                'video' => $media->telegram_file_id,
                'caption' => $media->title,
            ]);
        }

        return redirect()->back()->with('success', 'Media sent to Telegram!');
    }
}