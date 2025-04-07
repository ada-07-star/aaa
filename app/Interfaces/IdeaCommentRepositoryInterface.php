<?php

namespace App\Interfaces;

use App\Models\Idea;

interface IdeaCommentRepositoryInterface
{
    public function getIdeaComments(Idea $idea): array;
}
