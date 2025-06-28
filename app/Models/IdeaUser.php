<?php

namespace App\Models;

use App\Traits\HasCreatorUpdater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaUser extends Model
{
    /** @use HasFactory<\Database\Factories\IdeaUserFactory> */
    use HasFactory;
    use HasCreatorUpdater;

    protected $fillable = [
        'idea_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}
