<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleManagement extends Model
{
    protected $table = 'module_management';
    
    protected $fillable = ['name', 'enabled', 'icon', 'route', 'order'];
    
    protected $casts = ['enabled' => 'boolean'];
}
