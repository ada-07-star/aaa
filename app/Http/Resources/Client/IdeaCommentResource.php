<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class IdeaCommentResource extends JsonResource
{
    /**
     * وضعیت‌های ممکن برای نظرات
     */
    private const STATUS_TITLES = [
        'draft' => 'پیش نویس',
        'published' => 'قابل انتشار'
    ];

    /**
     * @OA\Schema(
     *     schema="IdeaCommentIndexResource",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="فناوری اطلاعات"),
     *     @OA\Property(property="idea",type="object",@OA\Property(property="title",type="string",example="موضوعات عام"), @OA\Property(property="id", type="integer", example=32323)),
     *     @OA\Property(property="comment_text",type="string",example="رواق کودک"),
     *     @OA\Property(property="like",type="integer",example="0"),
     *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01"),
     *     @OA\Property(property="status",type="object",@OA\Property(property="title",type="string",example="پیش نویس"), @OA\Property(property="slug", type="string", example="draft")),
     *     @OA\Property(property="created_by",type="object",@OA\Property(property="name",type="string",example="علی"), @OA\Property(property="email", type="string", example="ali@gmail.com")),
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'idea' => [
                'id' => $this->idea->id,
                'title' => $this->idea->title,
            ],
            'comment_text' => $this->comment_text,
            'parent_id' => $this->parent_id,
            'likes' => $this->likes,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'status' => [
                'title' => $this->getStatusTitle(),
                'slug' => $this->status,
            ],
            'created_by' => [
                'name' => $this->users->name,
                'email' => $this->users->email,
            ],
        ];
    }

    /**
     * دریافت عنوان وضعیت
     *
     * @return string
     */
    private function getStatusTitle(): string
    {
        if (!$this->status) {
            return 'نامشخص';
        }

        return self::STATUS_TITLES[$this->status] ?? $this->status;
    }
}
