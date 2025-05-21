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
     *         @OA\Property(property="title", type="string", example="فعال"),
     *         @OA\Property(property="slug", type="string", example="active")
     *     ),
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
            'current_state' => [
                'title' => $this->current_state,
                'slug' => $this->current_state,
            ],
            'submit_date_from' => $this->formatDate($this->submit_date_from),
            'submit_date_to' => $this->formatDate($this->submit_date_to),
            'language' => $this->whenLoaded('language', function () {
                return $this->language ? [
                    'name' => $this->language->name,
                    'id' => $this->language->id,
                ] : null;
            }),
            'department' => $this->whenLoaded('department', function () {
                return [
                    'id' => $this->department->id,
                    'title' => $this->department->title,
                ];
            }),
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->title,
                    ];
                });
            }),
        ];
    }
}
