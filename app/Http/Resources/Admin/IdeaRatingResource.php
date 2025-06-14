<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="IdeaRatingIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="idea_id", type="integer", example=1),
 *    @OA\Property(property="rate_number", type="integer", example=5),
 *   @OA\Property(property="user_id", type="integer", example=1),
 *   @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="IdeaRatingShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/IdeaShowResource"),
 *         @OA\Schema(
 *            @OA\Property(property="id", type="integer", example=1),
 *           @OA\Property(property="idea_id", type="integer", example=1),
 *          @OA\Property(property="rate_number", type="integer", example=5),
 *          @OA\Property(property="user_id", type="integer", example=1),
 *         @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 *         )
 *     }
 * )
 */
class IdeaRatingResource extends JsonResource
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
            'rate_number' => $this->rate_number,
            'user_id' => $this->user_id,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'idea_id' => $this->idea_id,
            'rate_number' => $this->rate_number,
            'user_id' => $this->user_id,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }
}
