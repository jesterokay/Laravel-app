<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name', 'type', 'value', 'description', 'start_date', 'end_date', 'applies_to', 'conditions',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'conditions' => 'array',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_promotion');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'promotion_sale');
    }
}