<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class TopicResource extends JsonResource
{
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }

    /**
     *  @OA\Schema(
     *     schema="TopicResource",
     *     type="object",
     *     allOf={
     *         @OA\Schema(ref="#/components/schemas/TopicResource"),
     *         @OA\Schema(
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="عنوان موضوع نمونه"),
     *     @OA\Property(property="department_id", type="integer", example=1),
     *     @OA\Property(property="language_id", type="integer", example=1),
     *     @OA\Property(property="current_state", type="string", example="draft"),
     *     @OA\Property(property="submit_date_from", type="string", format="date", example="1402/01/01"),
     *     @OA\Property(property="submit_date_to", type="string", format="date", example="1402/12/29"),
     *     @OA\Property(property="status", type="string", example="پیش نویس"),
     *     @OA\Property(property="is_archive", type="boolean", example=false),
     *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
     *          )
     *     }
     * )
     *
     */
    public function toArray(Request $request): array
    { 
        return [
            'title' => $this->title,
            'status' => $this->status,
            'category' => $this->categories->map(function ($category) {
                return [
                    'title' => $category->title,
                    'id' => $category->id,
                ];
            }),
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title
                ];
            }),
            'steps' => [
                'title' => 'تاریخ شروع ثبت ایده',
                'date' => $this->submit_date_from,
                'isCurrent' => "no"
            ],
            'current_state' => [
                'title' => $this->current_state_fa,
                'slug' => $this->current_state ?? 'pending'
            ],
            'submit_date_from' =>  $this->formatDate($this->submit_date_from),
            'submit_date_to' => $this->formatDate($this->submit_date_to),
        ];
    }
}
