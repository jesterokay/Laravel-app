<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_summary_id',
        'product_id',
        'adjustment_type',
        'quantity',
        'reason',
        'adjusted_at',
    ];

    protected $casts = [
        'adjusted_at' => 'datetime',
        'adjustment_type' => 'string',
    ];

    public function inventorySummary()
    {
        return $this->belongsTo(InventorySummary::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}