<?php

namespace App\Interfaces;

use App\Models\Idea;

interface IdeaRepositoryInterface
{
    public function getPublishedIdeasForTopic($topic);
    public function createIdea($data);
    public function findById($ideaId);
    public function updateIdea(Idea $idea, array $data);
    public function getAllIdeas(array $filters = [], string $sort = 'created_at');
    public function deleteIdea($ideaId);
}
