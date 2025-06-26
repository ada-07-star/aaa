<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="AdminTopicResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="عنوان موضوع نمونه"),
 *     @OA\Property(property="department_id", type="integer", example="12"),
 *     @OA\Property(property="language_id", type="integer", example="3"),
 *     @OA\Property(property="submit_date_from", type="string", format="date-time", example="2023-01-01 00:00:00"),
 *     @OA\Property(property="submit_date_to", type="string", format="date-time", example="2023-12-31 23:59:59"),
 *     @OA\Property(
 *         property="current_state",
 *         type="object",
 *         @OA\Property(property="title", type="string", example="غیرفعال"),
 *         @OA\Property(property="slug", type="string", example="pending")
 *     ),
 *     @OA\Property(property="status", type="string", example="پیش نویس"),
 *     @OA\Property(property="is_archive", type="boolean", example="false"),
 *     @OA\Property(property="created_at", type="date", example="1402/01/01"),
 * )
 */
class TopicResource extends JsonResource
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
            'title' => $this->title,
            'department_id' => $this->department_id,
            'language_id' => $this->language_id,
            'current_state' => [
                'title' => $this->current_state_fa,
                'slug' => $this->current_state ?? 'draft'
            ],
            'submit_date_from' => $this->formatDate($this->submit_date_from),
            'submit_date_to' => $this->formatDate($this->submit_date_to),
            'status' => $this->status,
            'is_archive' => $this->is_archive,
            'created_at' => $this->formatDate($this->created_at),
        ];
    }

    protected function forShow()
    {
        return [
            'title' => $this->title,
            'department_id' => $this->department_id,
            'language_id' => $this->language_id,
            'age_range' => $this->age_range,
            'gender' => $this->gender,
            'thumb_image' => $this->thumb_image,
            'cover_image' => $this->cover_image,
            'submit_date_from' => $this->formatDate($this->submit_date_from),
            'submit_date_to' => $this->formatDate($this->submit_date_to),
            'consideration_date_from' => $this->formatDate($this->consideration_date_from),
            'consideration_date_to' => $this->formatDate($this->consideration_date_to),
            'plan_date_from' => $this->formatDate($this->plan_date_from),
            'plan_date_to' => $this->formatDate($this->plan_date_to),
            'current_state' => [
                'title' => $this->current_state_fa,
                'slug' => $this->current_state ?? 'draft'
            ],
            'judge_number' => $this->judge_number,
            'minimum_score' => $this->minimum_score,
            'evaluation_id' => $this->evaluation_id,
            'status' => $this->status,
            'is_archive' => $this->is_archive,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
