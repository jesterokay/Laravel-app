<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Http;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'employees';

    protected $fillable = [
        'department_id',
        'position_id',
        'username',
        'password',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'salary',
        'status',
        'image'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            return null;
        }

        try {
            $response = Http::get("https://api.telegram.org/bot{$botToken}/getFile", [
                'file_id' => $this->image,
            ]);

            if ($response->successful() && $response->json('ok')) {
                $filePath = $response->json('result.file_path');
                return "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
            }
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return null;
        }

        return null;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}