<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'type', 'value', 'description', 'applies_to',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_product');
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'discount_sale');
    }
}