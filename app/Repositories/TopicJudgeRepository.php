<?php

namespace App\Repositories;

use App\Interfaces\TopicJudgeRepositoryInterface;
use App\Models\TopicJudge;

class TopicJudgeRepository implements TopicJudgeRepositoryInterface
{
    protected $model;

    public function __construct(TopicJudge $model)
    {
        $this->model = $model;
    }

    /**
     * Get all judges assigned to a specific topic
     * 
     * @param int $topicId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getJudgesByTopicId(int $topicId)
    {
        return $this->model
            ->with(['user:id,name,email', 'topic:id,title'])
            ->where('topic_id', $topicId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Assign a judge to a topic
     * 
     * @param int $userId
     * @param int $topicId
     * @return \App\Models\TopicJudge
     */
    public function assignJudgeToTopic(int $userId, int $topicId)
    {
        return $this->model->firstOrCreate([
            'user_id' => $userId,
            'topic_id' => $topicId,
        ]);
    }

    /**
     * Remove a judge from a topic
     * 
     * @param int $userId
     * @param int $topicId
     * @return bool
     */
    public function removeJudgeFromTopic(int $userId, int $topicId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->delete();
    }

    /**
     * Check if a judge is assigned to a topic
     * 
     * @param int $userId
     * @param int $topicId
     * @return bool
     */
    public function isJudgeAssignedToTopic(int $userId, int $topicId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->exists();
    }
}