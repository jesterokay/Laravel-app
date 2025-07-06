<?php
namespace Modules\Media\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Modules\Media\Models\Media;

class MediaController extends Controller
{
    private $channel_id = -1002808159169;
    private $bot_token = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
    private $max_file_size = 2097152000; // 2GB in bytes

    public function index()
    {
        $mediaItems = Media::latest()->get()->map(function ($media) {
            if ($media->media_type === 'document' && in_array($media->mime_type, ['video/mp4', 'video/avi', 'video/quicktime'])) {
                $media->display_type = 'video';
            } else {
                $media->display_type = $media->media_type;
            }
            return $media;
        });

        return view('media::media.index', ['media' => $mediaItems]);
    }

    public function create()
    {
        return view('media::media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:2097152|mimes:jpg,jpeg,png,webp,mp4,avi,mov,pdf,doc,docx,txt',
            'media_type' => 'required|in:image,video,document'
        ]);

        $file = $request->file('file');
        $media_type = $request->media_type;

        if ($media_type === 'video' && $file->getSize() > 52428800) {
            $media_type = 'document';
        }

        try {
            $result = $this->uploadToTelegram($file, $media_type, $request->title, $request->description);
            
            if ($result['success']) {
                Media::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'telegram_file_id' => $result['file_id'],
                    'telegram_file_path' => $result['file_path'],
                    'telegram_message_id' => $result['message_id'],
                    'media_type' => $media_type,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getClientMimeType(),
                    'original_filename' => $file->getClientOriginalName()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Media uploaded successfully to Telegram',
                    'redirect' => route('media.index')
                ]);
            }

            return back()->withErrors(['file' => 'Failed to upload file to Telegram: ' . $result['error']]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Upload error: ' . $e->getMessage()]);
        }
    }

    public function show(Media $media)
    {
        if ($media->media_type === 'document' && in_array($media->mime_type, ['video/mp4', 'video/avi', 'video/quicktime'])) {
            $media->display_type = 'video';
        } else {
            $media->display_type = $media->media_type;
        }
        return view('media::media.show', compact('media'));
    }

    public function edit(Media $media)
    {
        if ($media->media_type === 'document' && in_array($media->mime_type, ['video/mp4', 'video/avi', 'video/quicktime'])) {
            $media->display_type = 'video';
        } else {
            $media->display_type = $media->media_type;
        }
        return view('media::media.edit', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:2097152|mimes:jpg,jpeg,png,webp,mp4,avi,mov,pdf,doc,docx,txt',
            'media_type' => 'required|in:image,video,document',
        ]);

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $media_type = $request->media_type;

                if ($media_type === 'video' && $file->getSize() > 52428800) {
                    $media_type = 'document';
                }

                if (!empty($media->telegram_message_id)) {
                    $this->deleteFromTelegram($media->telegram_message_id);
                }

                $result = $this->uploadToTelegram($file, $media_type, $request->title, $request->description);
                
                if ($result['success']) {
                    $media->update([
                        'title' => $request->title,
                        'description' => $request->description,
                        'telegram_file_id' => $result['file_id'],
                        'telegram_file_path' => $result['file_path'],
                        'telegram_message_id' => $result['message_id'],
                        'media_type' => $media_type,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                        'original_filename' => $file->getClientOriginalName()
                    ]);
                } else {
                    return back()->withErrors(['file' => 'Failed to upload new file to Telegram: ' . $result['error']]);
                }
            } else {
                if (!empty($media->telegram_message_id)) {
                    $this->updateTelegramCaption($media->telegram_message_id, $request->title, $request->description);
                }
                $media->update($request->only(['title', 'description']));
            }

            return redirect()->route('media.show', $media)->with('success', 'Media updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Update error: ' . $e->getMessage()]);
        }
    }

    public function destroy(Media $media)
    {
        if (!empty($media->telegram_message_id)) {
            $this->deleteFromTelegram($media->telegram_message_id);
        }

        $media->delete();
        return redirect()->route('media.index')->with('success', 'Media deleted successfully');
    }

    private function uploadToTelegram($file, $media_type, $title, $description = null)
    {
        $endpoint = $this->getTelegramEndpoint($media_type);
        $field = $this->getTelegramField($media_type);

        $caption = $title;
        if ($description) {
            $caption .= "\n\n" . $description;
        }

        $params = [
            'chat_id' => $this->channel_id,
            'caption' => $caption,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => true,
            'disable_notification' => true,
        ];

        if ($media_type === 'video') {
            $params['supports_streaming'] = true;
            $params['width'] = 1280;
            $params['height'] = 720;
            $params['duration'] = 0;
        } elseif ($media_type === 'image') {
            $params['disable_content_type_detection'] = true;
        }

        try {
            $tempPath = $file->getRealPath();
            
            $response = Http::timeout(600)
                ->connectTimeout(30)
                ->retry(3, 2000)
                ->asMultipart()
                ->attach($field, fopen($tempPath, 'r'), $file->getClientOriginalName(), [
                    'Content-Type' => $file->getClientMimeType()
                ])
                ->post("https://api.telegram.org/bot{$this->bot_token}/{$endpoint}", $params);

            if ($response->successful()) {
                $result = $response->json()['result'];
                $file_id = $this->getFileIdFromResponse($result, $media_type);
                $message_id = $result['message_id'];

                $file_path = $this->getTelegramFilePath($file_id);

                return [
                    'success' => true,
                    'file_id' => $file_id,
                    'file_path' => $file_path,
                    'message_id' => $message_id
                ];
            }

            $error = $response->json()['description'] ?? 'Unknown error';
            return [
                'success' => false,
                'error' => $error
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function getTelegramFilePath($file_id)
    {
        $cacheKey = 'telegram_file_path_' . $file_id;
        
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($file_id) {
            try {
                $response = Http::timeout(30)
                    ->get("https://api.telegram.org/bot{$this->bot_token}/getFile", [
                        'file_id' => $file_id
                    ]);

                if ($response->successful()) {
                    return $response->json()['result']['file_path'];
                }
            } catch (\Exception $e) {
            }

            return null;
        });
    }

    private function updateTelegramCaption($message_id, $title, $description = null)
    {
        $caption = $title;
        if ($description) {
            $caption .= "\n\n" . $description;
        }

        try {
            Http::timeout(30)
                ->post("https://api.telegram.org/bot{$this->bot_token}/editMessageCaption", [
                    'chat_id' => $this->channel_id,
                    'message_id' => $message_id,
                    'caption' => $caption,
                    'parse_mode' => 'HTML',
                ]);
        } catch (\Exception $e) {
        }
    }

    private function deleteFromTelegram($message_id)
    {
        try {
            Http::timeout(30)
                ->post("https://api.telegram.org/bot{$this->bot_token}/deleteMessage", [
                    'chat_id' => $this->channel_id,
                    'message_id' => $message_id,
                ]);
        } catch (\Exception $e) {
        }
    }

    private function getTelegramEndpoint($media_type)
    {
        return match ($media_type) {
            'image' => 'sendPhoto',
            'video' => 'sendVideo',
            'document' => 'sendDocument',
            default => 'sendDocument'
        };
    }

    private function getTelegramField($media_type)
    {
        return match ($media_type) {
            'image' => 'photo',
            'video' => 'video',
            'document' => 'document',
            default => 'document'
        };
    }

    private function getFileIdFromResponse($result, $media_type)
    {
        if ($media_type === 'image') {
            return $result['photo'][count($result['photo']) - 1]['file_id'];
        }

        return match ($media_type) {
            'video' => $result['video']['file_id'],
            'document' => $result['document']['file_id'],
            default => $result['document']['file_id']
        };
    }

    public function getFileUrl(Media $media)
    {
        if (empty($media->telegram_file_path)) {
            return null;
        }

        return "https://api.telegram.org/file/bot{$this->bot_token}/{$media->telegram_file_path}";
    }

    public function download(Media $media)
    {
        $fileUrl = $this->getFileUrl($media);
        if (!$fileUrl) {
            return redirect()->back()->with('error', 'File not found');
        }

        try {
            return response()->stream(function () use ($fileUrl, $media) {
                $client = new \GuzzleHttp\Client(['stream' => true]);
                $response = $client->get($fileUrl, ['timeout' => 600]);
                $body = $response->getBody();

                while (!$body->eof()) {
                    echo $body->read(8192);
                    flush();
                }
            }, 200, [
                'Content-Type' => $media->mime_type,
                'Content-Disposition' => 'inline; filename="' . $media->original_filename . '"',
                'Content-Length' => $media->file_size,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to fetch file: ' . $e->getMessage());
        }
    }
}