<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class IdeaCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'idea' => [
                'id' => $this->idea->id,
                'title' => $this->idea->title,
                'current_state' => $this->idea->current_state,
                'participation_type' => $this->idea->participation_type,
            ],
            'comment_text' => $this->comment_text,
            'parent_id' => $this->parent_id,
            'likes' => $this->likes,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'status' => [
                'title' => $this->getStatusTitle($this->status),
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

    }
}
