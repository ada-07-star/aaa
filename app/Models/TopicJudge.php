<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopicJudge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topic_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user (judge) associated with this assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the topic associated with this assignment.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}