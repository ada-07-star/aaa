<?php

namespace App\Interfaces;

use App\Models\Topic;

interface IdeaRepositoryInterface
{
    public function createIdea($data);
    public function findById($ideaId);
    public function updateIdea(array $data, $ideaId);
    public function getAllIdeas(array $filters = [], string $sort = 'created_at');
    public function deleteIdea($ideaId);
}
