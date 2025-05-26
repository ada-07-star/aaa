<?php

namespace App\Repositories;

use App\Interfaces\IdeaRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaLog;
use App\Models\Topic;

class IdeaRepository implements IdeaRepositoryInterface
{
    public function getPublishedIdeasForTopic($topic)
    {
        $topicModel = Topic::with(['ideas' => function ($query) {
            $query->where('is_published', 1);
        }])->find($topic);
        
        return $topicModel ? $topicModel->ideas : collect();
    }

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

        if (!empty($data['users'])) {
            $idea->users()->attach($data['users']);
        }
        return $idea;
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
                            'email' => $user->email,
                        ];
                    }),
                ],
            ],
        ];
    }

    public function showIdeaRepository(int $idea)
    {

        $idea = Idea::findOrFail($idea);

        return [
            'id' => $idea->id,
            'topic_id' => $idea->topic->isEmpty ? null : [
                'title' => $idea->topic->title,
                'id' => $idea->topic->id,
            ],
            'title' => $idea->title,
            'description' => $idea->description,
            'is_published' => $idea->is_published,
            'created_at' => $idea->created_at,
            'current_state' => $idea->current_state,
            'participation_type' => $idea->participation_type,
            'users' => $idea->users->map(function ($user) {
                return [
                    'uuid' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
        ];
    }
}
