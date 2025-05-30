<?php

namespace App\Http\Controllers\Client\v1;

use App\Interfaces\IdeaRepositoryInterface;
use App\Http\Resources\Client\IdeaResource;
use App\Http\Requests\UpdateIdeaRequest;
use App\Http\Requests\StoreIdeaRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use App\Models\Idea;
use Throwable;

/**
 * @OA\Schema(
 *     schema="IdeaResponse",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="success"),
 *     @OA\Property(property="message", type="string", example="ایده با موفقیت ثبت شد."),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(
 *             property="idea",
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(
 *                 property="topic",
 *                 type="object",
 *                 @OA\Property(property="title", type="string", example="Technology"),
 *                 @OA\Property(property="id", type="integer", example=1)
 *             ),
 *             @OA\Property(property="title", type="string", example="New Product Idea"),
 *             @OA\Property(property="description", type="string", example="Detailed description"),
 *             @OA\Property(property="is_published", type="boolean", example=true),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01 12:00:00"),
 *             @OA\Property(
 *                 property="current_state",
 *                 type="object",
 *                 @OA\Property(property="title", type="string", example="draft"),
 *                 @OA\Property(property="slug", type="string", example="draft")
 *             ),
 *             @OA\Property(
 *                 property="participation_type",
 *                 type="object",
 *                 @OA\Property(property="title", type="string", example="open"),
 *                 @OA\Property(property="slug", type="string", example="open")
 *             ),
 *             @OA\Property(
 *                 property="users",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="uuid", type="string", format="uuid"),
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="email", type="string", format="email")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class IdeaController extends Controller
{
    use ApiResponse;

    protected $ideaRepository;

    public function __construct(IdeaRepositoryInterface $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/app/topics/{topic}/ideas",
     *     summary="دریافت لیست ایده‌های یک موضوع خاص",
     *     description="این endpoint لیست ایده‌های منتشر شده یا قابل انتشار یک موضوع خاص را برمی‌گرداند.",
     *     tags={"ایده - user"},
     *     @OA\Parameter(
     *         name="topic",
     *         in="path",
     *         description="شناسه موضوع",
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
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Ideas retrieved successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="ideas",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             example=32323
     *                         ),
     *                         @OA\Property(
     *                             property="topic",
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
     *                             property="title",
     *                             type="string",
     *                             example="رواق کودک"
     *                         ),
     *                         @OA\Property(
     *                             property="description",
     *                             type="string",
     *                             example="رواق کودک"
     *                         ),
     *                         @OA\Property(
     *                             property="is_published",
     *                             type="boolean",
     *                             example=false
     *                         ),
     *                         @OA\Property(
     *                             property="created_at",
     *                             type="string",
     *                             format="date-time",
     *                             example="2025-02-12 16:27:01"
     *                         ),
     *                         @OA\Property(
     *                             property="current_state",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="title",
     *                                 type="string",
     *                                 example="active"
     *                             ),
     *                             @OA\Property(
     *                                 property="slug",
     *                                 type="string",
     *                                 example="active"
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="participation_type",
     *                             type="object",
     *                             @OA\Property(
     *                                 property="title",
     *                                 type="string",
     *                                 example="فردی"
     *                             ),
     *                             @OA\Property(
     *                                 property="slug",
     *                                 type="string",
     *                                 example="individual"
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="users",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(
     *                                     property="uuid",
     *                                     type="string",
     *                                     example="sasa-4343kmn43k4-4343m4km34=434"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="name",
     *                                     type="string",
     *                                     example="نام"
     *                                 ),
     *                                 @OA\Property(
     *                                     property="email",
     *                                     type="string",
     *                                     example="davood@gmail.com"
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="موضوع یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Topic not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Internal server error"
     *             )
     *         )
     *     )
     * )
     */
    public function index($topic)
    {
        try {
            $ideas = $this->ideaRepository->getPublishedIdeasForTopic($topic);
            return $this->successResponse(
                ['ideas' => IdeaResource::collection($ideas)],
                'ایده‌ها با موفقیت دریافت شدند'
            );
        } catch (Throwable $exception) {
            return exception_response_exception(request(), $exception);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/app/idea",
     *     summary="ایده جدید ثبت کنید",
     *     tags={"ایده - user"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "topic_id", "is_published", "participation_type", "final_score", "id"},
     *             @OA\Property(property="title", type="string", example="New Product Idea"),
     *             @OA\Property(property="description", type="string", example="Detailed description"),
     *             @OA\Property(property="topic_id", type="integer", example=1),
     *             @OA\Property(property="is_published", type="boolean", example=true),
     *             @OA\Property(property="participation_type", type="string", enum={"team", "individual"}, example="team"),
     *             @OA\Property(property="final_score", type="number", format="float", example=0.0),
     *             @OA\Property(property="id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Idea created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IdeaResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(StoreIdeaRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $idea = $this->ideaRepository->createIdea($validatedData);

        return $this->successResponse(
            new IdeaResource($idea),
            'ایده با موفقیت ثبت شد.',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/app/idea/{id}",
     *     tags={"ایده - user"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Idea data including related users",
     *         @OA\JsonContent(
     *             required={"title", "description", "topic_id", "is_published", "participation_type", "final_score"},
     *             @OA\Property(property="title", type="string", example="New Product Idea", maxLength=255),
     *             @OA\Property(property="description", type="string", example="Detailed description"),
     *             @OA\Property(property="topic_id", type="integer", example=1),
     *             @OA\Property(property="is_published", type="boolean", example=true),
     *             @OA\Property(
     *                 property="participation_type", 
     *                 type="string", 
     *                 enum={"team", "individual"}, 
     *                 example="team"
     *             ),
     *             @OA\Property(property="final_score", type="number", format="float", example=0.0),
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                 @OA\Items(
     *                     type="integer",
     *                     format="int64",
     *                     description="User IDs to associate with the idea",
     *                     example=1
     *                 ),
     *                 description="Array of user IDs to associate with the idea"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Idea created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IdeaResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="field_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The field name is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $results = $this->ideaRepository->showIdeaRepository($id);
        if (!$results) {
            return $this->notFoundResponse('موضوع مورد نظر یافت نشد.');
        }

        return $this->successResponse($results, 'اطلاعات با موفقیت دریافت شد.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/v1/app/idea/{ideaId}",
     *     tags={"ایده - user"},
     *     summary="به‌روزرسانی ایده",
     *     description="این endpoint برای به‌روزرسانی عنوان ایده با شناسه مشخص استفاده می‌شود.",
     *     operationId="updateIdea",
     *     @OA\Parameter(
     *         name="ideaId",
     *         in="path",
     *         required=true,
     *         description="شناسه یکتای ایده",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="داده‌های مورد نیاز برای به‌روزرسانی ایده",
     *         @OA\JsonContent(
     *             required={"title", "description", "participation_type"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="عنوان جدید ایده",
     *                 maxLength=255,
     *                 description="عنوان ایده (حداکثر 255 کاراکتر)"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="توضیحات کامل درباره ایده جدید",
     *                 description="محتوای کامل توضیحات ایده"
     *             ),
     *             @OA\Property(
     *                 property="participation_type",
     *                 type="string",
     *                 enum={"individual", "team"},
     *                 example="individual",
     *                 description="نوع مشارکت در ایده"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *        @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="ایده با موفقیت به‌روزرسانی شد."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="idea",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="عنوان جدید ایده"),
     *                     @OA\Property(property="description", type="string", example="توضیحات کامل"),
     *                     @OA\Property(
     *                         property="participation_type",
     *                         type="string",
     *                         example="individual",
     *                         description="نوع مشارکت: team یا individual"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2023-05-01T12:00:00Z"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="ورودی نامعتبر",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ایده پیدا نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Idea not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateIdeaRequest $request, $ideaId)
    {
        $idea = Idea::findOrFail($ideaId)->users()->first()->id;

        if ($idea == Auth::user()->id) {
            $ideaUpdate = $this->ideaRepository->updateIdea($request->all(), $ideaId);
            $results = $this->ideaRepository->formatUpdateIdea($ideaUpdate);
            return $this->successResponse($results, 'ایده با موفقیت به‌روزرسانی شد.');
        }

        return $this->errorResponse('شما مجاز به ویرایش این ایده نیستید.', 422);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //
    }
}
