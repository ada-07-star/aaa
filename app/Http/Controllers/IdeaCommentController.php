<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\IdeaComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Idea Comments",
 *     description="Operations about idea comments"
 * )
 */
class IdeaCommentController extends Controller
{

    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/app/idea/{idea}/comment",
     *     operationId="storeIdeaComment",
     *     tags={"Idea Comments"},
     *     summary="Create a new comment for an idea",
     *     description="Allows users to post comments on active ideas",
     *     @OA\Parameter(
     *         name="idea",
     *         in="path",
     *         description="ID of the idea to comment on",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"comment_text"},
     *             @OA\Property(
     *                 property="comment_text",
     *                 type="string",
     *                 example="این یک نظر تستی است",
     *                 minLength=3,
     *                 maxLength=1000
     *             ),
     *             @OA\Property(
     *                 property="parent_id",
     *                 type="integer",
     *                 description="ID of parent comment if this is a reply",
     *                 example=null
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="نظر با موفقیت ثبت شد"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="comment",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=123
     *                     ),
     *                     @OA\Property(
     *                         property="idea",
     *                         type="object",
     *                         @OA\Property(
     *                             property="title",
     *                             type="string",
     *                             example="عنوان ایده"
     *                         ),
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=2
     *                         ),
     *                         @OA\Property(
     *                             property="current_state",
     *                             type="string",
     *                             example="active"
     *                         ),
     *                         @OA\Property(
     *                             property="participation_type",
     *                             type="string",
     *                             example="individual"
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="comment_text",
     *                         type="string",
     *                         example="این یک نظر تستی است"
     *                     ),
     *                     @OA\Property(
     *                         property="parent_id",
     *                         type="integer",
     *                         nullable=true,
     *                         example=null
     *                     ),
     *                     @OA\Property(
     *                         property="likes",
     *                         type="integer",
     *                         example=0
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2025-02-12 16:27:01"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2025-02-12 16:27:01"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="string",
     *                         example="active"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="در حال حاضر امکان ثبت نظر برای این ایده وجود ندارد"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="comment_text",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="متن نظر الزامی است"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request, Idea $idea)
    {
        if ($idea->current_state !== 'archived') {
            return response()->json([
                'status' => 'error',
                'message' => 'در حال حاضر امکان ثبت نظر برای این ایده وجود ندارد'
            ], 403);

        }


        $comment = IdeaComment::create([
            'comment_text' => $request->comment_text,
            'idea_id' => $idea->id,
            'parent_id' => $request->parent_id ?? null,
            'likes' => 0,
            'status' => 'active',
            'created_by' => $idea->id,
        ]);

        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'نظر با موفقیت ثبت شد',
            'data' => [
                'comment' => [
                    'id' => $comment->id,
                    'idea' => [
                        'title' => $idea->title,
                        'id' => $idea->id,
                        'current_state' => $idea->current_state,
                        'participation_type' => $idea->participation_type,
                    ],
                    'comment_text' => $comment->comment_text,
                    'parent_id' => $comment->parent_id,
                    'likes' => $comment->likes,
                    'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $comment->updated_at->format('Y-m-d H:i:s'),
                    'status' => $comment->status,
                    // 'created_by' => [
                    //     'id' => auth()->id,
                    //     'name' => auth()->name,
                    //     'email' => auth()->email,
                    // ]
                ]
            ]
        ], 201);
    }

    public function show(IdeaComment $ideaComment)
    {
        //
    }

    public function update(Request $request, IdeaComment $ideaComment)
    {
        //
    }

    public function destroy(IdeaComment $ideaComment)
    {
        //
    }
}
