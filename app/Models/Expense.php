<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id',
        'amount', 'description', 'expense_date'
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}