<?php

namespace App\Repositories;

use App\Interfaces\IdeaRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaLog;

class IdeaRepository implements IdeaRepositoryInterface
{
    public function createIdea($data)
    {
        $idea = Idea::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'topic_id' => $data['topic_id'],
            'is_published' => $data['is_published'],
            'participation_type' => $data['participation_type'],
            'final_score' => $data['final_score'],
        ]);

        if (isset($data['users'])) {
            $idea->users()->attach($data['users']);
        }
        return $idea;
    }

    public function formatIdeaResponse($idea)
    {

        return [
            'status' => 'success',
            'message' => 'ایده با موفقیت ثبت شد.',
            'data' => [
                'idea' => [
                    'id' => $idea->id,
                    'topic' => [
                        'title' => $idea->topic->title,
                        'id' => $idea->topic->id,
                    ],
                    'title' => $idea->title,
                    'description' => $idea->description,
                    'is_published' => $idea->is_published,
                    'created_at' => $idea->created_at->format('Y-m-d H:i:s'),
                    'current_state' =>  [
                        'title' =>  $idea->current_state,
                        'slug' =>  $idea->current_state,
                    ],
                    'participation_type' =>  [
                        'title' =>  $idea->participation_type,
                        'slug' =>  $idea->participation_type,
                    ],
                    'users' => $idea->users->map(function ($user) {
                        return [
                            'uuid' => $user->uuid,
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                    }),
                ],
            ],
        ];
    }

    public function updateIdea($data, $ideaId)
    {
        $idea = Idea::find($ideaId);
        if ($idea->description !== $data['description']) {
            IdeaLog::create([
                'idea_id' => $idea->id,
                'description' => $idea->description,
            ]);
        }

        $idea->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'participation_type' => $data['participation_type'],
        ]);


        if (isset($data['users'])) {
            $idea->users()->sync($data['users']);
        }
        return $idea->fresh();
    }

    public function formatUpdateIdea($ideaUpdate)
    {
        if (!$ideaUpdate) {
            return response()->json([
                'status' => 'error',
                'message' => 'ایده مورد نظر یافت نشد.',
            ], 404);
        }

        return [
            'status' => 'success',
            'message' => 'ایده با موفقیت به‌روزرسانی شد.',
            'data' => [
                'idea' => [
                    'id' => $ideaUpdate->id,
                    'topic' => [
                        'title' => $ideaUpdate->topic->title,
                        'id' => $ideaUpdate->topic->id,
                    ],
                    'title' => $ideaUpdate->title,
                    'description' => $ideaUpdate->description,
                    'is_published' => $ideaUpdate->is_published,
                    'created_at' => $ideaUpdate->created_at,
                    'participation_type' => $ideaUpdate->participation_type,
                    'users' => $ideaUpdate->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'family' => $user->family,
                        ];
                    }),
                ],
            ],
        ];
    }
}
