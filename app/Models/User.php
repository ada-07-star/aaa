<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**  
 * @OA\Schema(  
 *     schema="User",  
 *     type="object",  
 *     @OA\Property(property="id", type="integer", example=1),  
 *     @OA\Property(property="name", type="string", example="Jane Doe"),  
 *     @OA\Property(property="email", type="string", example="jane.doe@example.com"),  
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),  
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-02T00:00:00Z"),  
 * )  
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function ideas(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, 'idea_users');
    }
}
