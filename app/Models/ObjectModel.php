<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'evaluation_id',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];
    protected $table = 'objects';
}
