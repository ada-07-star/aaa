<?php

namespace App\Repositories;

use App\Enums\ParticipationTypeEnum;
use App\Models\Idea;

class IdeaRepository
{
    public function updateIdea(array $data): array
    {
        $idea = Idea::findOrFail($data['id']);

        $idea->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'is_published' => $data['is_published'],
            'participation_type' => ParticipationTypeEnum::getValues(),
        ]);

        return [
            'id' => $idea->id,
            'topic' => [
                'title' => $idea->topic->title,
                'id' => $idea->topic->id,
            ],
            'title' => $idea->title,
            'description' => $idea->description,
            'is_published' => $idea->is_published,
            'created_at' => $idea->created_at->format('Y-m-d H:i:s'),
            'current_state' => [
                'title' => $idea->currentState->title,
                'slug' => $idea->currentState->slug,
            ],
            'participation_type' => [
                'title' => ParticipationTypeEnum::getValues(),
                'slug' => $idea->participation_type,
            ],
            'users' => $idea->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'family' => $user->family,
                ];
            }),
        ];
    }
}
