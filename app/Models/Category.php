<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'department_id',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'topic_categories');
    }
}
