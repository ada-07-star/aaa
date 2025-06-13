<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="IdeaUserIndexResource",
 *     type="object",
 *     title="Idea User Resource",
 *     description="Idea user relationship data",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="idea_id",
 *         type="integer",
 *         example=10
 *     ),
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=305),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john@example.com")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date",
 *         example="1402/03/15"
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="IdeaUserShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/IdeaUserShowResource"),
 *         @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="idea_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 *         )
 *     }
 * )
 */
class IdeaUserResource extends JsonResource
{
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }
    public function toArray(Request $request): array
    {
        $mode = $this->additional['mode'] ?? 'index';

        return match ($mode) {
            'show' => $this->forShow(),
            default => $this->forIndex(),
        };
    }
    protected function forIndex()
    {
        return [
            'id' => $this->id,
            'idea_id' => $this->idea_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email
            ],
            'created_at' => $this->formatDate($this->created_at)
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'idea_id' => $this->idea_id,
            'user_id' => $this->user_id,
            'created_at' => $this->formatDate($this->created_at),
        ];
    }
}
