<?php

namespace App\Http\Controllers;

use App\Interfaces\IdeaCommentRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaComment;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Idea Comments",
 *     description="Operations about idea comments"
 * )
 */
class IdeaCommentController extends Controller
{
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
     *     summary="Get list of comments for an idea",
     *     description="Returns list of published comments and user's unpublished comments",
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
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="لیست نظرات با موفقیت دریافت شد"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="comments",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=32323
     *                         ),
     *                         @OA\Property(
     *                             property="idea",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="title",
     *                                 type="string",
     *                                 example="موضوعات عام"
     *                             ),
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                                 example=32323
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="comment_text",
     *                             type="string",
     *                             example="رواق کودک"
     *                         ),
     *                         @OA\Property(
     *                             property="likes",
     *                             type="integer",
     *                             example=0
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             format="date-time",
     *                             example="2025-02-12 16:27:01"
     *                         ),
     *                         @OA\Property(
     *                             property="status",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="title",
     *                                 type="string",
     *                                 example="پیش نویس"
     *                             ),
     *                             @OA\Property(
     *                                 property="slug",
     *                                 type="string",
     *                                 example="draft"
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="created_by",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                                 example="نام"
     *                             ),
     *                             @OA\Property(
     *                                 property="family",
     *                                 type="string",
     *                                 example="نام خانوادگی"
     *                             )
     *                         )
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
     *                 example="این ایده منتشر نشده است"
     *             )
     *         )
     *     )
     * )
     */
    public function index(Idea $idea)
    {
        $response = $this->ideaCommentRepository->getIdeaComments($idea);

        if ($response['status'] === 'error') {
            return response()->json([
                'status' => $response['status'],
                'message' => $response['message']
            ], $response['code']);
        }

        return response()->json($response);
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
        $response = $this->ideaCommentRepository->createIdeaComment($request, $idea);

        return response()->json(
            ['status' => $response['status'], 'message' => $response['message'], 'data' => $response['data']],
            $response['code']
        );
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
