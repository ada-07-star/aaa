<?php

namespace App\Repositories;

use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaComment;
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
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhere(function ($q) {
                        $q->where('status', '!=', 'published')
                            ->where('created_by', Auth::id());
                    });
            })
            ->with(['idea', 'creator'])
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
            'active' => 'قابل انتشار',
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
}
