<?php

namespace App\Interfaces;

interface TopicJudgeRepositoryInterface
{
    /**
     * Get all judges assigned to a specific topic
     * 
     * @param int $topicId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getJudgesByTopicId(int $topicId);

    /**
     * Assign a judge to a topic
     * 
     * @param int $userId
     * @param int $topicId
     * @return \App\Models\TopicJudge
     */
    public function assignJudgeToTopic(int $userId, int $topicId);

    /**
     * Remove a judge from a topic
     * 
     * @param int $userId
     * @param int $topicId
     * @return bool
     */
    public function removeJudgeFromTopic(int $userId, int $topicId);

    /**
     * Check if a judge is assigned to a topic
     * 
     * @param int $userId
     * @param int $topicId
     * @return bool
     */
    public function isJudgeAssignedToTopic(int $userId, int $topicId);
}