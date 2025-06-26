<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="IdeaIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="فناوری اطلاعات"),
 *     @OA\Property(property="topic_id", type="integer", example=1),
 *     @OA\Property(property="is_published", type="boolean", example=true),
 *     @OA\Property(property="total_view", type="integer", example=100),
 *     @OA\Property(property="current_state", type="string", example="draft"),
 *     @OA\Property(property="participation_type", type="string", example="individual"),
 *     @OA\Property(property="final_score", type="integer", example=100),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="IdeaShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/IdeaShowResource"),
 *         @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="topic_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="فناوری اطلاعات"),
 *     @OA\Property(property="description", type="string", example="توضیحات ایده"),
 *     @OA\Property(property="is_published", type="boolean", example=true),
 *     @OA\Property(property="total_view", type="integer", example=100),
 *     @OA\Property(property="current_state", type="string", example="draft"),
 *     @OA\Property(property="participation_type", type="string", example="individual"),
 *     @OA\Property(property="final_score", type="integer", example=100),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="IdeaStoreResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=5)
 * )
 */
class IdeaResource extends JsonResource
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
            'store' => $this->forStore(),
            'show' => $this->forShow(),
            default => $this->forIndex(),
        };
    }


    protected function forIndex()
    {
        return [
            'id' => $this->id,
            'topic_id' => $this->topic_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'current_state' => $this->current_state,
            'participation_type' => $this->participation_type,
            'final_score' => $this->final_score,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }

    protected function forStore()
    {
        return [
            'id' => $this->id,
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'topic_id' => $this->topic_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'total_view' => $this->total_view,
            'current_state' => $this->current_state,
            'participation_type' => $this->participation_type,
            'final_score' => $this->final_score,
            'created_at' => $this->formatDate($this->created_at),
        ];
    }
}
