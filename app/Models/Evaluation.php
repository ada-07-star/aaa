<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'department_id',
        'evaluation_rating_id',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];
}
