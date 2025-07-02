<?php
namespace Modules\Media\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TestTelegramBot extends Controller
{
    private $channel_id = -1002808159169;
    private $bot_token = null;

    public function __construct()
    {
        $this->bot_token = env('TELEGRAM_BOT_TOKEN');
    }

    public function testTelegram() {
        return view ('media::test-telegram');
    }

    public function testTelegramBot(Request $request)
    {
        try {
            // Test sending a simple text message to verify bot token and chat ID
            $response = Http::post("https://api.telegram.org/bot{$this->bot_token}/sendMessage", [
                'chat_id' => $this->channel_id,
                'text' => 'Test message from Laravel application'
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['ok']) && $result['ok'] === true) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Bot token and chat ID are working correctly',
                        'response' => $result
                    ]);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send test message',
                'error' => $response->json()['description'] ?? 'Unknown error',
                'response' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while testing Telegram bot',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}