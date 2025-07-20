<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description',
        'price', 'stock_quantity', 'sku', 'barcode', 'image'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->getTelegramImageUrl($this->image);
    }

    protected function getTelegramImageUrl($fileId)
    {
        if (!$fileId) {
            return null;
        }

        $client = new Client();
        $botToken = '7738267715:AAGisTRywG6B0-Bwn-JW-tmiMAjFfTxLOdE';

        try {
            $response = $client->get("https://api.telegram.org/bot{$botToken}/getFile", [
                'query' => ['file_id' => $fileId],
                'timeout' => 10,
            ]);
            $data = json_decode($response->getBody(), true);
            if ($data['ok']) {
                $filePath = $data['result']['file_path'];
                return "https://api.telegram.org/file/bot{$botToken}/{$filePath}";
            }
        } catch (RequestException $e) {
            // Log the error or handle it as needed
        }

        return null;
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventorySummaries()
    {
        return $this->hasMany(InventorySummary::class);
    }
}