<?php

namespace App\Repositories;

use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class IdeaCommentsRepository  implements IdeaCommentRepositoryInterface
{
    use ApiResponse;
    public function getIdeaComments($id)
    {
        $idea = Idea::with(['comments' => function ($query) {
            $query->where('status', 'published');
        }])->where('is_published', '1')->find($id);

        return $idea->comments;
    }

    /**
     * ثبت نظر جدید برای ایده
     *
     * @param Request $request
     * @param Idea $idea
     * @return array
     */
    public function createIdeaComment(Request $request, Idea $idea): array
    {
        if ($idea->current_state === 'archived') {
            return [
                'status' => 'error',
                'message' => 'در حال حاضر امکان ثبت نظر برای این ایده وجود ندارد',
                'code' => 403
            ];
        }

        $comment = IdeaComment::create([
            'comment_text' => $request->comment_text,
            'idea_id' => $idea->id,
            'parent_id' => $request->parent_id ?? null,
            'likes' => 0,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return $this->successResponse($this->formatNewComment($comment, $idea), 'نظر با موفقیت ثبت شد.', 201);
    }

    /**
     * فرمت دهی اطلاعات نظر جدید
     *
     * @param IdeaComment $comment
     * @param Idea $idea
     * @return array
     */
    private function formatNewComment(IdeaComment $comment, Idea $idea): array
    {
        return [
            'id' => $comment->id,
            'idea' => [
                'title' => $idea->title,
                'id' => $idea->id,
                'current_state' => $idea->current_state,
                'participation_type' => $idea->participation_type,
            ],
            'comment_text' => $comment->comment_text,
            'likes' => $comment->likes,
            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            'status' => [
                'title' => $this->getStatusTitle($comment->status),
                'slug' => $comment->status,
            ],
            'created_by' => [
                'uuid' => Auth::user()->uuid,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ];
    }
}
