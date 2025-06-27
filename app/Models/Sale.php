<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 
        'user_id',
        'invoice_number', // Add this
        'total_amount', 
        'tax_amount', 
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}