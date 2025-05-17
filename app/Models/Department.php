<?php

namespace App\Models;

use App\Traits\HasCreatorUpdater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;
    use HasCreatorUpdater;
    
    protected $fillable = [
        'title',
        'descriptions',
        'status',
        'created_by',
        'updated_by'
    ];

}
