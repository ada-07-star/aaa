<?php

namespace App\Interfaces;

use App\Models\Idea;
use Illuminate\Http\Request;

interface IdeaCommentRepositoryInterface
{
    public function getIdeaComments($id);
    public function createIdeaComment(Request $request, Idea $idea);
}
