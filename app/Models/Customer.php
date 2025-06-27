<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name',
        'email', 'phone', 'address', 'status', 'image'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}