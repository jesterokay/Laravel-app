<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'product_id', 'type', 'value', 'description', 'start_date', 'end_date', 'applies_to',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promotion');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'promotion_sale');
    }
}