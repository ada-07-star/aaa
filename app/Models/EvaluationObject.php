<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationObject extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'department_id',
        'description',
        'status',
        'status',
        'created_by',
    ];
}
