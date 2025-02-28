<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentAccess extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentAccessFactory> */
    use HasFactory;
    
    protected $fillable = [  
        'user_id',  
        'role_id',  
        'department_id',  
        'status',  
        'created_by',  
        'updated_by',  
    ];  
}
