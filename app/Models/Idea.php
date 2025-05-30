<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CurrentStateEnum;
use App\Enums\ParticipationTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     schema="Idea",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="عنوان اوکی شد."),
 * )
 */
class Idea extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaFactory> */
    use HasFactory;

    protected $table = 'ideas'; // نام جدول
    protected $primaryKey = 'id'; // نام کلید اصلی
    
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

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'idea_users');
    }

    public function comments()
    {
        return $this->hasMany(IdeaComment::class, 'idea_id');
    }

    public function ratings()
    {
        return $this->hasMany(IdeaRating::class);
    }

    public function logs()
    {
        return $this->hasMany(IdeaLog::class);
    }
}
