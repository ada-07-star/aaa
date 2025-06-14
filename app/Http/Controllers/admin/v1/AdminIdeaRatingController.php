<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\IdeaRatingResource;
use Illuminate\Http\Request;
use App\Interfaces\IdeaRatingRepositoryInterface;
use App\Traits\ApiResponse;
use Throwable;

class AdminIdeaRatingController extends Controller
{
    use ApiResponse;
    protected $ideaRatingRepository;

    public function __construct(IdeaRatingRepositoryInterface $ideaRatingRepository)
    {
        $this->ideaRatingRepository = $ideaRatingRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/idea-ratings",
     *     summary="گرفتن لیست امتیازهای ایده‌ها",
     *     description="Returns a paginated list of idea ratings",
     *     operationId="getIdeaRating",
     *     tags={"Admin Idea-Ratings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="idea_id",
     *         in="query",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
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
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="ideasRatings",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/IdeaRatingIndexResource")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="اطلاعات با موفقیت دریافت شدند"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="خطای احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to fetch idea ratings"
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $ideaRatings = $this->ideaRatingRepository->getAllIdeaRatings($request);
            $ideaRatings = IdeaRatingResource::collection($ideaRatings);
            if (!$ideaRatings || $ideaRatings->isEmpty()) {
                return $this->errorResponse('امتیازی برای ایده یافت نشد', 404);
            }
            return $this->successResponse(
                ['ideasRatings' => $ideaRatings],
                'اطلاعات با موفقیت دریافت شدند'
            );
        } catch (Throwable $exception) {
            return exception_response_exception($request, $exception);
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
     *     path="/api/v1/admin/idea-ratings",
     *     summary="ایجاد یا آپدیت امتیاز به یک ایده",
     *     tags={"Admin Idea-Ratings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"idea_id", "user_id", "rate_number"},
     *             @OA\Property(property="idea_id", type="integer", example=1, description="ID of the idea being rated"),
     *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user who is rating"),
     *             @OA\Property(property="rate_number", type="integer", minimum=1, maximum=5, example=4, description="Rating value (1-5)")
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
     *                 @OA\Property(property="id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "idea_id": {"The idea id field is required."},
     *                     "rating": {"The rating must be between 1 and 5."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error occurred"),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     )
     * )
     *
     * Store a newly created or update existing idea rating in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'idea_id' => 'required|integer|exists:ideas,id',
                'user_id' => 'required|integer|exists:users,id',
                'rate_number' => 'required|integer|min:1|max:5',
            ]);

            $ideaRating = $this->ideaRatingRepository->createIdeaRating($validated);

            return $this->successResponse(
                ['ideasRatings' => new IdeaRatingResource($ideaRating)],
                'امتیاز با موفقیت ثبت شد'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->errors(), 422);
        } catch (Throwable $exception) {
            return exception_response_exception($request, $exception);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/idea-ratings/{id}",
     *     summary="گرفتن اطلاعات امتیاز یک ایده",
     *     description="برگرداندن اطلاعات امتیاز مربوط به یک ایده ",
     *     operationId="getIdeaRatings",
     *     tags={"Admin Idea-Ratings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the idea-rating",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=3
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
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="ideasRatings",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/IdeaRatingIndexResource")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="اطلاعات با موفقیت دریافت شدند"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="خطای احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Failed to fetch idea ratings"
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $ideaRating = $this->ideaRatingRepository->findById($id);
            $ideaRating = new IdeaRatingResource($ideaRating);
            if (!$ideaRating) {
                return $this->errorResponse('Idea rating not found', 404);
            }
            return $this->successResponse(
                ['ideasRatings' => $ideaRating],
                'اطلاعات با موفقیت دریافت شدند'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch idea ratings', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *  
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/idea-ratings/{id}",
     *     summary="ویرایش امتیاز ایده",
     *     description="بروزرسانی امتیاز ثبت‌شده توسط یک کاربر برای یک ایده",
     *     tags={"Admin Idea-Ratings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه امتیاز ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rate_number"},
     *             @OA\Property(property="rate_number", type="integer", minimum=1, maximum=5, example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="امتیاز با موفقیت به‌روزرسانی شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="امتیاز پیدا نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="اطلاعات مورد نظر پیدا نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "rate_number": {"The rate number must be between 1 and 5."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطای سرور رخ داده است")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'rate_number' => 'required|integer|min:1|max:5'
            ]);
            $this->ideaRatingRepository->updateIdeaRating($id, $validated);

            return $this->successResponse(
                null,
                'امتیاز با موفقیت به‌روزرسانی شد'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('خطای سرور رخ داده است', 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/idea-ratings/{id}",
     *     summary="حذف امتیاز ایده",
     *     description="حذف امتیاز ثبت شده برای یک ایده",
     *     operationId="deleteIdeaRating",
     *     tags={"Admin Idea-Ratings"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="شناسه امتیاز ایده",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="امتیاز با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="امتیاز یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="امتیازی برای ایده یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطا در حذف امتیاز")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $ideaRating = $this->ideaRatingRepository->findById($id);
            if (!$ideaRating) {
                return $this->notFoundResponse('امتیازی برای ایده یافت نشد', 404);
            }
            $this->ideaRatingRepository->deleteIdeaRating($id);
            return $this->successResponse(null, 'امتیاز با موفقیت حذف شد');
        } catch (\Exception $e) {
            return $this->errorResponse('خطا در حذف امتیاز', 500);
        }
    }
}
