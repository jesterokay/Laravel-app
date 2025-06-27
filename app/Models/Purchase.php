<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'user_id',
        'total_amount', 'tax_amount', 'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}