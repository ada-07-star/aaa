<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory;
    
    protected $fillable = [
        'title',
        'department_id',
        'language_id',
        'age_range',
        'gender',
        'thumb_image',
        'cover_image',
        'submit_date_from',
        'submit_date_to',
        'consideration_date_from',
        'consideration_date_to',
        'plan_date_from',
        'plan_date_to',
        'current_state',
        'judge_number',
        'minimum_score',
        'evaluation_id',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'topic_categories');
    }

}
