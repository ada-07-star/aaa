<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

class IdeaResource extends JsonResource
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
            'topic' => [
                'title' => $this->topic->title,
                'id' => $this->topic->id
            ],
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => $this->is_published,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d'),
            'current_state' => [
                'title' => $this->current_state,
                'slug' => $this->current_state
            ],
            'participation_type' => [
                'title' => $this->participation_type,
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
