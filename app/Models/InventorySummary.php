<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stock_quantity', 'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}