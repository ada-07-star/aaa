<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class, 'idea_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(IdeaComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(IdeaComment::class, 'parent_id');
    }
}