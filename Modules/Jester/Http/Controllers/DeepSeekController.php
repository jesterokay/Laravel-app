<?php

namespace Modules\Jester\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekController extends Controller
{
    /**
     * Handles both displaying the DeepSeek chat form and processing its submission.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function chat(Request $request)
    {
        $deepSeekReply = null;
        $errorMessage = null;

        // Check if the request method is POST (meaning the form was submitted)
        if ($request->isMethod('post')) {
            // Validate the incoming request data
            $request->validate([
                'prompt' => 'required|string|max:1000', // Ensure prompt is present and within limits
            ]);

            // --- OpenRouter Specific Configuration ---
            $openRouterApiKey = env('OPENROUTER_API_KEY');
            $openRouterApiUrl = 'https://openrouter.ai/api/v1/chat/completions';
            $modelToUse = 'deepseek/deepseek-r1-0528-qwen3-8b:free';
            // --- End OpenRouter Specific Configuration ---

            // Basic check for API key presence
            if (empty($openRouterApiKey)) {
                $errorMessage = 'OpenRouter API Key is missing in your .env file.';
            } else {
                try {
                    // Make the HTTP POST request to OpenRouter API
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $openRouterApiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->timeout(90)
                    ->connectTimeout(15)
                    ->post($openRouterApiUrl, [
                        'model' => $modelToUse,
                        'messages' => [
                            ['role' => 'user', 'content' => $request->input('prompt')],
                        ],
                        'temperature' => 0.7,
                    ]);

                    // Check if the API call failed
                    if ($response->failed()) {
                        $errorMessage = "OpenRouter DeepSeek API call failed: " . $response->status() . " - " . $response->body();
                    } else {
                        $responseData = $response->json();
                        if (isset($responseData['choices'][0]['message']['content'])) {
                            $deepSeekReply = $responseData['choices'][0]['message']['content'];
                        } else {
                            $errorMessage = 'OpenRouter DeepSeek API response did not contain expected chat content.';
                        }
                    }
                } catch (\Throwable $e) {
                    $errorMessage = 'An unexpected error occurred while contacting the AI: ' . $e->getMessage();
                }
            }

            // Check if the request expects a JSON response (AJAX)
            if ($request->wantsJson()) {
                if ($errorMessage) {
                    return response()->json(['error' => $errorMessage], 500);
                }
                return response()->json(['reply' => $deepSeekReply]);
            }

            // For non-AJAX requests, redirect with session data
            return redirect()->route('jester.deepseek.chat_page')
                             ->withInput()
                             ->with('deepseek_reply', $deepSeekReply)
                             ->with('deepseek_error', $errorMessage);
        }

        // For GET requests, render the view
        return view('jester::deepseek.index', [
            'deepseekReply' => session('deepseek_reply'),
            'deepseekError' => session('deepseek_error'),
            'oldPrompt' => old('prompt')
        ]);
    }
}