<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicCategory extends Model
{
    /** @use HasFactory<\Database\Factories\TopicCategoryFactory> */
    use HasFactory;
    protected $fillable = ['topic_id', 'category_id'];
}
