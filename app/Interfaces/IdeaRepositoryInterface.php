<?php

namespace App\Interfaces;

interface IdeaRepositoryInterface
{
    public function createIdea(array $data);
    public function formatIdeaResponse($idea);
}
