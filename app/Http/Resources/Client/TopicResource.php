<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class TopicResource extends JsonResource
{

    /**
     *
     * @param string $date
     * @return string|null
     */
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }

    /**
     * @OA\Schema(
     *     schema="ClientTopicResource",
     *     type="object",
     *     description="Topic resource for client application",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         example=1,
     *         description="Unique identifier of the topic"
     *     ),
     *     @OA\Property(
     *         property="title",
     *         type="string",
     *         example="عنوان موضوع نمونه",
     *         description="Title of the topic"
     *     ),
     *     @OA\Property(
     *         property="current_state",
     *         type="object",
     *         description="Current state of the topic",
     *         @OA\Property(
     *             property="title",
     *             type="string",
     *             example="در حال بررسی"
     *         ),
     *         @OA\Property(
     *             property="slug",
     *             type="string",
     *             example="in-review"
     *         )
     *     ),
     *     @OA\Property(
     *         property="submit_date_from",
     *         type="string",
     *         format="date",
     *         example="1402-01-15",
     *         description="Formatted start date for idea submission"
     *     ),
     *     @OA\Property(
     *         property="submit_date_to",
     *         type="string",
     *         format="date",
     *         example="1402-02-15",
     *         description="Formatted end date for idea submission"
     *     ),
     *     @OA\Property(
     *         property="tags",
     *         type="array",
     *         description="List of tags associated with the topic",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="تگ نمونه")
     *         )
     *     ),
     *     @OA\Property(
     *         property="steps",
     *         type="array",
     *         description="Timeline of important dates in the topic lifecycle",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="تاریخ شروع ثبت ایده"),
     *             @OA\Property(
     *                 property="submit_date_from",
     *                 type="string",
     *                 format="date",
     *                 example="1402-01-15"
     *             ),
     *             @OA\Property(
     *                 property="isCurrent",
     *                 type="string",
     *                 enum={"yes", "no"},
     *                 example="no"
     *             )
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
            'submit_date_from' =>  $this->formatDate($this->submit_date_from),
            'submit_date_to' => $this->formatDate($this->submit_date_to),
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title
                ];
            }),
            'steps' => [
                [
                    'title' => 'تاریخ شروع ثبت ایده',
                    'submit_date_from' =>  $this->formatDate($this->submit_date_from),
                    'isCurrent' => 'no',
                ],
                [
                    'title' => 'تاریخ پایان ثبت ایده',
                    'submit_date_to' =>  $this->formatDate($this->submit_date_to),
                    'isCurrent' => 'no',
                ],
                [
                    'title' => 'تاریخ شروع داوری',
                    'consideration_date_from' => $this->formatDate($this->consideration_date_from),
                    'isCurrent' => 'no',
                ],
                [
                    'title' => 'تاریخ پایان داوری',
                    'consideration_date_to' => $this->formatDate($this->consideration_date_to),
                    'isCurrent' => 'no',
                ],
                [
                    'title' => 'تاریخ شروع برنامه‌ریزی',
                    'plan_date_from' => $this->formatDate($this->plan_date_from),
                    'isCurrent' => 'no',
                ],
                [
                    'title' => 'تاریخ پایان برنامه‌ریزی',
                    'plan_date_to' => $this->formatDate($this->plan_date_to),
                    'isCurrent' => 'no',
                ]
            ],
        ];
    }
}
