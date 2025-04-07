<?php

namespace App\Interfaces;

use App\Models\Idea;
use Illuminate\Http\Request;

interface IdeaCommentRepositoryInterface
{
    public function getIdeaComments(Idea $idea): array;
    public function createIdeaComment(Request $request, Idea $idea): array;
}
