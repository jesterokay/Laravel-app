<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'employees';

    protected $fillable = [
        'department_id',
        'position_id',
        'username',
        'password',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'salary',
        'status',
        'image'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ?? null;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}