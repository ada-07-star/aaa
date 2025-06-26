<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="TopicCategoryIndexResource",
 *     type="object",
 *     title="Topic Category Resource",
 *     description="Topic Category relationship data",
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
 *         @OA\Property(property="id", type="integer", example=5),
 *         @OA\Property(property="topic_id", type="integer", example=2),
 *         @OA\Property(property="category_id", type="integer", example=1),
 *         @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 *     ),
 * )
 */
class TopicCategoryResource extends JsonResource
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
            'topic_id' => $this->topic_id,
            'category_id' => $this->category_id,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'topic_id' => $this->topic_id,
            'category_id' => $this->category_id,
            'created_at' => $this->formatDate($this->created_at),
        ];
    }
}
