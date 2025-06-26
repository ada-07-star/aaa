<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class IdeaResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="IdeaStoreResponse",
     *     type="object",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="title", type="string", example="فناوری اطلاعات"),
     *     @OA\Property(property="topic_id", type="integer", example=1),
     *     @OA\Property(property="description", type="string", example="توضیحات مربوط به یک ایده"),
     *     @OA\Property(property="is_published", type="boolean", example=false),
     *     @OA\Property(property="current_state", type="string", example="draft"),
     *     @OA\Property(property="participation_type", type="string", example="individual"),
     *     @OA\Property(property="final_score", type="integer", example=100),
     *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
     * )
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'topic' => [
                'title' => $this->topic->title,
                'id' => $this->topic->id
            ],
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'participation_type' => [
                'title' => $this->participation_type_fa,
                'slug' => $this->participation_type
            ],
            'users' => $this->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
            })
        ];
    }
}
