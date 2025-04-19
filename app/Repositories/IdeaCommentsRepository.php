<?php

namespace App\Repositories;

use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdeaCommentsRepository  implements IdeaCommentRepositoryInterface
{
    public function getIdeaComments(Idea $idea): array
    {
        if (!$idea->is_published) {
            return [
                'status' => 'error',
                'message' => 'این ایده منتشر نشده است',
                'code' => 403
            ];
        }

        $comments = IdeaComment::where('idea_id', $idea->id)
            ->Where(function ($q) {
                $q->where('status', '!=', 'published');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'status' => 'success',
            'message' => 'لیست نظرات با موفقیت دریافت شد',
            'data' => [
                'comments' => $comments->map(function ($comment) {
                    return $this->formatComment($comment);
                })
            ]
        ];
    }

    public function getStatusTitle(string $status): string
    {
        $titles = [
            'draft' => 'پیش نویس',
            'published' => 'قابل انتشار',
        ];

        return $titles[$status] ?? $status;
    }

    private function formatComment(IdeaComment $comment): array
    {
        return [
            'id' => $comment->id,
            'idea' => [
                'title' => $comment->idea->title,
                'id' => $comment->idea->id,
            ],
            'comment_text' => $comment->comment_text,
            'likes' => $comment->likes,
            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            'status' => [
                'title' => $this->getStatusTitle($comment->status),
                'slug' => $comment->status,
            ],
            'created_by' => [
                'name' => $comment->creator->name,
                'email' => $comment->creator->email,
            ]
        ];
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

        return [
            'status' => 'success',
            'message' => 'نظر با موفقیت ثبت شد',
            'data' => [
                'comment' => $this->formatNewComment($comment, $idea)
            ],
            'code' => 201
        ];
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
