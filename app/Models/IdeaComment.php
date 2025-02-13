<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_text',
        'idea_id',
        'parent_id',
        'likes',
        'status',
        'created_by',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent()
    {
        return $this->belongsTo(IdeaComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(IdeaComment::class, 'parent_id');
    }
}