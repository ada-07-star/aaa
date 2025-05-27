<?php

namespace App\Http\Resources;

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
     * @OA\Schema(
     *     schema="TopicResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="عنوان موضوع نمونه"),
     *     @OA\Property(
     *         property="current_state",
     *         type="object",
     *         @OA\Property(property="title", type="string", example="غیرفعال"),
     *         @OA\Property(property="slug", type="string", example="draft")
     *     ),
     * @OA\Property(property="participation_type",type="object",
     *         @OA\Property( property="title",type="string",example="فردی" ),
     *         @OA\Property(property="slug", type="string",example="individual")
     *     ),
     *     @OA\Property(property="status", type="string", example="پیش نویس"),
     *     @OA\Property(property="is_archive", type="boolean", example="false"),
     *     @OA\Property(property="submit_date_from", type="string", format="date-time", example="2023-01-01 00:00:00"),
     *     @OA\Property(property="submit_date_to", type="string", format="date-time", example="2023-12-31 23:59:59"),
     *     @OA\Property(
     *         property="language",
     *         type="object",
     *         @OA\Property(property="name", type="string", example="فارسی"),
     *         @OA\Property(property="id", type="integer", example=1)
     *     ),
     *     @OA\Property(
     *         property="department",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="title", type="string", example="دپارتمان نمونه")
     *     ),
     *     @OA\Property(
     *         property="categories",
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="دسته‌بندی نمونه")
     *         )
     *     )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'department_id' => $this->department_id,
            'language_id' => $this->language_id,
            'current_state' => $this->current_state,
            'submit_date_from' => $this->formatDate($this->submit_date_from),
            'submit_date_to' => $this->formatDate($this->submit_date_to),
            'status' => $this->status,
            'is_archive' => $this->is_archive,
            'created_at' => $this->formatDate($this->created_at),
        ];
    }
}
