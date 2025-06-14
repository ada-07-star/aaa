<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="IdeaLogIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="idea_id", type="integer", example=1),
 *    @OA\Property(property="description", type="string", example="وضعیت به حالت 'در حال بررسی' تغییر یافت."),
 *   @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="IdeaLogShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/IdeaLogShowResource"),
 *         @OA\Schema(
 *            @OA\Property(property="id", type="integer", example=1),
 *           @OA\Property(property="idea_id", type="integer", example=1),
 *          @OA\Property(property="description", type="string", example="وضعیت به حالت 'در حال بررسی' تغییر یافت."),
 *         @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 *         )
 *     }
 * )
 */
class IdeaLogResource extends JsonResource
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
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }

    protected function forShow()
    {
        return [
            ...$this->forIndex(),
            // Add any additional fields needed for the show view
        ];
    }
}