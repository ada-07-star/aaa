<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="TopicTagIndexResource",
 *     type="object",
 *             @OA\Property(property="id", type="integer", example="1"),
 *             @OA\Property(property="topic_id", type="integer", example="1"),
 *             @OA\Property(property="tag_id", type="integer", example="4"),
 *             @OA\Property(property="created_at", type="date", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="TopicTagShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TopicTagIndexResource"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", example="1"),
 *             @OA\Property(property="topic_id", type="integer", example="1"),
 *             @OA\Property(property="tag_id", type="integer", example="4"),
 *             @OA\Property(property="created_at", type="date", format="date", example="1402/01/01")
 *         )
 *     }
 * )
 */
class TopicTagResource extends JsonResource
{
     private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }
    public function toArray(Request $request)
    {
        return [
            // 'id' => $this->id,
            'topic_id' => $this->topic_id,
            'tag_id' => $this->tag_id,
            'created_at' => $this->formatDate($this->created_at),
        ];
    }
}
