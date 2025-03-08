<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CurrentStateEnum;
use App\Enums\ParticipationTypeEnum;

class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'description',
        'is_published',
        'current_state',
        'participation_type',
        'final_score',
    ];

    protected $casts = [
        'current_state' => CurrentStateEnum::class,
        'participation_type' => ParticipationTypeEnum::class,
    ];

    public function topics()
    {
        return $this->belongsTo(Topic::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
