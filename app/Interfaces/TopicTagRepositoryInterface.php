<?php

namespace App\Interfaces;

interface TopicTagRepositoryInterface
{
    public function getAllFilteredTopicTags(array $filters);
    public function createTopicTag(array $data);
} 