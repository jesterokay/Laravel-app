<?php

namespace Modules\Media\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Media\Models\Media;
use Illuminate\Support\Facades\Http;

class MediaController extends Controller
{
    private $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
    private $chatId = '1601089836';
    private $apiUrl = 'https://api.telegram.org/bot';

    public function index()
    {
        $media = Media::all();
        return view('media::index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'media' => 'required|file|mimes:jpg,png,mp4|max:10240',
        ]);

        $file = $request->file('media');
        $mediaType = in_array($file->getClientOriginalExtension(), ['jpg', 'png']) ? 'image' : 'video';

        // Upload to Telegram
        $field = $mediaType === 'image' ? 'photo' : 'video';
        $endpoint = $mediaType === 'image' ? 'sendPhoto' : 'sendVideo';

        $response = Http::attach(
            $field,
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post("{$this->apiUrl}{$this->botToken}/{$endpoint}", [
            'chat_id' => $this->chatId,
        ]);

        $fileId = $mediaType === 'image'
            ? $response->json()['result']['photo'][count($response->json()['result']['photo']) - 1]['file_id']
            : $response->json()['result']['video']['file_id'];

        Media::create([
            'title' => $request->title,
            'description' => $request->description,
            'telegram_file_id' => $fileId,
            'media_type' => $mediaType,
            'user_id' => auth()->id() ?? 1,
        ]);

        return redirect()->route('media.index')->with('success', 'Media uploaded to Telegram successfully!');
    }
}