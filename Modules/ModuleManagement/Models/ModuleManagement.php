<?php

namespace Modules\ModuleManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\ModuleManagement\Database\Factories\ModuleManagementFactory;

class ModuleManagement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'description', 'enabled'];

    // protected static function newFactory(): ModuleManagementFactory
    // {
    //     // return ModuleManagementFactory::new();
    // }
}
