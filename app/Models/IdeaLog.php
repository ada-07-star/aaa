<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaLog extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaLogFactory> */
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'description',
    ];
}
