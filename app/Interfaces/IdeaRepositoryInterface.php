<?php

namespace App\Interfaces;

use App\Models\Topic;

interface IdeaRepositoryInterface
{
    public function createIdea($data);
    public function formatIdeaResponse($idea);
    public function updateIdea(array $data, $ideaId);
    public function formatUpdateIdea($ideaUpdate);
    public function showIdeaRepository(int $idea);
    public function getPublishedIdeasForTopic(Topic $topic);
    
}
