<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date',
        'total_sales', 'total_tax', 'total_quantity'
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];
    
}