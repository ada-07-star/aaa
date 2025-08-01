<?php

namespace App\Http\Controllers\Client\v1;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Resources\Client\IdeaCommentResource;
use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IdeaComment;
use App\Traits\ApiResponse;
use App\Models\Idea;
use Throwable;

/**
 * @OA\Schema(
 *     schema="IdeaComment",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="content", type="string", example="This is a comment"),
 *     @OA\Property(property="likes", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class IdeaCommentController extends Controller
{
    use ApiResponse;
    private $ideaCommentRepository;

    public function __construct(IdeaCommentRepositoryInterface $ideaCommentRepository)
    {
        $this->ideaCommentRepository = $ideaCommentRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/app/idea/{idea}/comment",
     *     operationId="getIdeaComments",
     *     tags={"Idea Comments"},
     *     summary="دریافت لیست نظرات یک ایده",
     *     description="دریافت لیست نظرات منتشر شده از یک ایده",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="idea",
     *         in="path",
     *         description="ID of the idea",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="ideaComments", ref="#/components/schemas/IdeaCommentIndexResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="تظرات ایده با موفقیت دریافت شد")
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
     *                 example="این ایده منتشر نشده است"
     *             )
     *         )
     *     )
     * )
     */
    public function index($id)
    {
        try {
            $response = $this->ideaCommentRepository->getIdeaComments($id);
            return $this->successResponse(
                IdeaCommentResource::collection($response),
                'لیست نظرات با موفقیت دریافت شد'
            );
        } catch (NotFoundHttpException $exception) {
            return $this->notFoundResponse($exception->getMessage());
        } catch (Throwable $exception) {
            return exception_response_exception(request(), $exception);
        }
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
        try {
            $validated = $request->validate([
                'comment_text' => 'required|string|min:3|max:1000',
                'parent_id' => 'nullable|integer|exists:idea_comments,id'
            ]);

            $comment = $this->ideaCommentRepository->createIdeaComment($request, $idea);

            return $this->successResponse(
                new IdeaCommentResource($comment),
                'نظر با موفقیت ثبت شد',
                201
            );
        } catch (Throwable $exception) {
            return exception_response_exception(request(), $exception);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/app/idea/{ideaId}/comment_rate",
     *     summary="Toggle like on a comment",
     *     description="Like or unlike a comment and return the updated comment",
     *     tags={"Comments"},
     *     security={{"bearerAuth": {}}},
     *     
     *     @OA\Parameter(
     *         name="ideaId",
     *         in="path",
     *         description="ID of the idea",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"comment_id"},
     *             @OA\Property(
     *                 property="comment_id",
     *                 type="integer",
     *                 description="ID of the comment to like/unlike",
     *                 example=4
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Like status toggled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="تصمیم شما ثبت شد."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="comment",
     *                     ref="#/components/schemas/IdeaComment"
     *                 )
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="نظر یافت نشد."
     *             )
     *         )
     *     ),
     *     
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
     *                     property="comment_id",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     )
     * )
     */
    public function toggleLike(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|integer|exists:idea_comments,id',
        ]);

        $comment = IdeaComment::find($request->comment_id);

        if ($comment) {
            $comment->increment('likes');

            return $this->successResponse(
                ['comment' => new IdeaCommentResource($comment->fresh())],
                'نظر شما ثبت شد.'
            );
        }

        return $this->notFoundResponse('نظر یافت نشد.');
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
