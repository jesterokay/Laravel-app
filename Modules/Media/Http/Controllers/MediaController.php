<?php
namespace Modules\Media\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Modules\Media\Models\Media;

class MediaController extends Controller
{
    private $channel_id = -1002808159169;
    private $bot_token = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';

    public function index()
    {
        // Check all media records and remove those deleted from Telegram
        $mediaItems = Media::latest()->get();
        foreach ($mediaItems as $mediaItem) {
            if (!empty($mediaItem->telegram_message_id)) {
                $response = Http::get("https://api.telegram.org/bot{$this->bot_token}/getChat?chat_id={$this->channel_id}");
                if ($response->successful()) {
                    $messageResponse = Http::post("https://api.telegram.org/bot{$this->bot_token}/getChatHistory", [
                        'chat_id' => $this->channel_id,
                        'offset' => $mediaItem->telegram_message_id,
                        'limit' => 1
                    ]);
                    
                    if ($messageResponse->successful() && empty($messageResponse->json()['result']['messages'])) {
                        $mediaItem->delete();
                    }
                }
            }
        }
        
        return view('media::media.index', [
            'media' => Media::latest()->get()
        ]);
    }

    public function create()
    {
        return view('media::media.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:51200|mimes:jpg,jpeg,png,mp4,pdf,doc,docx',
            'media_type' => 'required|in:image,video,document'
        ]);

        $file = $request->file('file');
        $media_type = $request->media_type;
        $endpoint = $this->getTelegramEndpoint($media_type);
        $field = $this->getTelegramField($media_type);

        try {
            $fileContent = fopen($file->getRealPath(), 'r');
            
            $params = [
                'chat_id' => $this->channel_id,
                'caption' => $request->title,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true
            ];

            if ($media_type === 'video') {
                $params['supports_streaming'] = true;
            }

            $response = Http::timeout(120)
                ->asMultipart()
                ->attach($field, $fileContent, $file->getClientOriginalName(), [
                    'Content-Type' => $file->getClientMimeType()
                ])
                ->post("https://api.telegram.org/bot{$this->bot_token}/{$endpoint}", $params);

            fclose($fileContent);

            if ($response->successful()) {
                $result = $response->json()['result'];
                $file_id = $this->getFileIdFromResponse($result, $media_type);
                $message_id = $result['message_id'];

                $filePathResponse = Http::get("https://api.telegram.org/bot{$this->bot_token}/getFile?file_id={$file_id}");
                $file_path = $filePathResponse->successful() ? $filePathResponse->json()['result']['file_path'] : null;

                Media::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'telegram_file_id' => $file_id,
                    'telegram_file_path' => $file_path,
                    'telegram_message_id' => $message_id,
                    'media_type' => $media_type
                ]);

                return redirect()->route('media.index')->with('success', 'File uploaded successfully');
            }

            return back()->withErrors(['file' => 'Failed to upload file to Telegram: ' . ($response->json()['description'] ?? 'Unknown error')]);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Upload error: ' . $e->getMessage()]);
        }
    }

    public function show(Media $media)
    {
        // Verify if message still exists in Telegram
        $response = Http::get("https://api.telegram.org/bot{$this->bot_token}/getChat?chat_id={$this->channel_id}");
        if ($response->successful() && !empty($media->telegram_message_id)) {
            $messageResponse = Http::post("https://api.telegram.org/bot{$this->bot_token}/getChatHistory", [
                'chat_id' => $this->channel_id,
                'offset' => $media->telegram_message_id,
                'limit' => 1
            ]);
            
            if ($messageResponse->successful() && empty($messageResponse->json()['result']['messages'])) {
                $media->delete();
                return redirect()->route('media.index')->with('error', 'Media no longer exists in Telegram and has been removed from the database');
            }
        }
        
        return view('media::media.show', compact('media'));
    }

    public function edit(Media $media)
    {
        // Verify if message still exists in Telegram
        $response = Http::get("https://api.telegram.org/bot{$this->bot_token}/getChat?chat_id={$this->channel_id}");
        if ($response->successful() && !empty($media->telegram_message_id)) {
            $messageResponse = Http::post("https://api.telegram.org/bot{$this->bot_token}/getChatHistory", [
                'chat_id' => $this->channel_id,
                'offset' => $media->telegram_message_id,
                'limit' => 1
            ]);
            
            if ($messageResponse->successful() && empty($messageResponse->json()['result']['messages'])) {
                $media->delete();
                return redirect()->route('media.index')->with('error', 'Media no longer exists in Telegram and has been removed from the database');
            }
        }
        
        return view('media::media.edit', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:51200|mimes:jpg,jpeg,png,mp4,pdf,doc,docx',
            'media_type' => 'required|in:image,video,document',
        ]);

        try {
            // Verify if message still exists in Telegram
            $response = Http::get("https://api.telegram.org/bot{$this->bot_token}/getChat?chat_id={$this->channel_id}");
            if ($response->successful() && !empty($media->telegram_message_id)) {
                $messageResponse = Http::post("https://api.telegram.org/bot{$this->bot_token}/getChatHistory", [
                    'chat_id' => $this->channel_id,
                    'offset' => $media->telegram_message_id,
                    'limit' => 1
                ]);
                
                if ($messageResponse->successful() && empty($messageResponse->json()['result']['messages'])) {
                    $media->delete();
                    return redirect()->route('media.index')->with('error', 'Media no longer exists in Telegram and has been removed from the database');
                }
            }

            if ($request->hasFile('file')) {
                // Delete old Telegram message if exists
                if (!empty($media->telegram_message_id)) {
                    Http::post("https://api.telegram.org/bot{$this->bot_token}/deleteMessage", [
                        'chat_id' => $this->channel_id,
                        'message_id' => $media->telegram_message_id,
                    ]);
                }

                // Upload new file as new Telegram message
                $file = $request->file('file');
                $endpoint = $this->getTelegramEndpoint($request->media_type);
                $field = $this->getTelegramField($request->media_type);

                $fileContent = fopen($file->getRealPath(), 'r');

                $params = [
                    'chat_id' => $this->channel_id,
                    'caption' => $request->title,
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true
                ];

                if ($request->media_type === 'video') {
                    $params['supports_streaming'] = true;
                }

                $response = Http::timeout(120)
                    ->asMultipart()
                    ->attach($field, $fileContent, $file->getClientOriginalName(), [
                        'Content-Type' => $file->getClientMimeType()
                    ])
                    ->post("https://api.telegram.org/bot{$this->bot_token}/{$endpoint}", $params);

                fclose($fileContent);

                if (!$response->successful()) {
                    return back()->withErrors(['file' => 'Failed to upload new file to Telegram']);
                }

                $result = $response->json()['result'];
                $file_id = $this->getFileIdFromResponse($result, $request->media_type);
                $message_id = $result['message_id'];

                $filePathResponse = Http::get("https://api.telegram.org/bot{$this->bot_token}/getFile?file_id={$file_id}");
                $file_path = $filePathResponse->successful() ? $filePathResponse->json()['result']['file_path'] : null;

                $media->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'telegram_file_id' => $file_id,
                    'telegram_file_path' => $file_path,
                    'telegram_message_id' => $message_id,
                    'media_type' => $request->media_type,
                ]);
            } else {
                // No new file - just update title/description and edit caption on Telegram
                if (!empty($media->telegram_message_id)) {
                    Http::post("https://api.telegram.org/bot{$this->bot_token}/editMessageCaption", [
                        'chat_id' => $this->channel_id,
                        'message_id' => $media->telegram_message_id,
                        'caption' => $request->title,
                        'parse_mode' => 'HTML',
                    ]);
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
            Http::post("https://api.telegram.org/bot{$this->bot_token}/deleteMessage", [
                'chat_id' => $this->channel_id,
                'message_id' => $media->telegram_message_id,
            ]);
        }

        $media->delete();
        return redirect()->route('media.index')->with('success', 'Media deleted successfully');
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
        return match ($media_type) {
            'image' => $result['photo'][count($result['photo']) - 1]['file_id'],
            'video' => $result['video']['file_id'],
            'document' => $result['document']['file_id'],
            default => $result['document']['file_id']
        };
    }

    public function handleWebhook(Request $request)
    {
        $update = $request->all();
        
        if (isset($update['channel_post']['chat']['id']) && $update['channel_post']['chat']['id'] == $this->channel_id) {
            if (isset($update['channel_post']['message_id'])) {
                $message_id = $update['channel_post']['message_id'];
                
                if (isset($update['channel_post']['delete_chat_photo']) || 
                    isset($update['channel_post']['deleted_messages']) ||
                    (isset($update['channel_post']['edited_channel_post']) && 
                     !isset($update['channel_post']['photo']) && 
                     !isset($update['channel_post']['video']) && 
                     !isset($update['channel_post']['document']))) {
                    
                    $media = Media::where('telegram_message_id', $message_id)->first();
                    if ($media) {
                        $media->delete();
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}