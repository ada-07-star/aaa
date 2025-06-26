<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TopicCategoryResource;
use App\Interfaces\TopicCategoryRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminTopicCategoryController extends Controller
{
    use ApiResponse;
    protected $topicCategoryRepository;

    public function __construct(TopicCategoryRepositoryInterface $topicCategoryRepository)
    {
        $this->topicCategoryRepository = $topicCategoryRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/admin/topic-categories",
     *     tags={"Admin Topic-Categories"},
     *     summary="لیست ارتباط موضوع و دسته‌بندی",
     *     description="نمایش لیستی از ارتباط‌های بین موضوعات و دسته‌بندی‌ها",
     *     operationId="getTopicCategories",
     *     @OA\Parameter(
     *         name="topic_id",
     *         in="query",
     *         description="فیلتر بر اساس شناسه موضوع",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="فیلتر بر اساس شناسه دسته‌بندی",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(ref="#/components/schemas/TopicCategoryIndexResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The idea_id field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "idea_id": {"The idea_id field is required."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['topic_id', 'category_id']);
            $topicCategory = $this->topicCategoryRepository->getTopicCategories($filters);
            $topicCategory = TopicCategoryResource::collection($topicCategory);
            $topicCategory->additional(['mode' => 'index']);

            return $this->successResponse(['topicCategory' => $topicCategory], 'اطلاعات ارتباط موضوع و دسته بندی با موفقیت دریافت شد.');
        } catch (\Exception $e) {
            return $this->errorResponse('مشکل در استخراج اطلاعات', 500);
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
     *     path="/api/v1/admin/topic-categories",
     *     summary="افزودن ارتباط موضوع و دسته بندی",
     *     description="ایجاد ارتباط جدید بین موضوع و دسته‌بندی",
     *     operationId="storeTopicCategories",
     *     tags={"Admin Topic-Categories"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"topic_id", "category_id"},
     *             @OA\Property(
     *                 property="topic_id", 
     *                 type="integer", 
     *                 description="شناسه موضوع",
     *                 example=6
     *             ),
     *             @OA\Property(
     *                 property="category_id", 
     *                 type="integer", 
     *                 description="شناسه دسته‌بندی",
     *                 example=2
     *             )
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
     *                 example="ارتباط بین موضوع و دسته بندی با موفقیت ایجاد شد"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="شناسه ارتباط ایجاد شده",
     *                     example=1
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "topic_id": {"The topic_id field is required."},
     *                     "category_id": {"The category_id field is required."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $topicCategory = $this->topicCategoryRepository->createTopicCategories($request->only(['topic_id', 'category_id']));
            return $this->successResponse(['id' => $topicCategory->id], 'ارتباط بین موضوع و دسته بندی با موفقیت ایجاد شد');
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/topic-categories/{id}",
     *     summary="مشاهده جزئیات ارتباط موضوع و دسته‌بندی",
     *     description="دریافت اطلاعات یک رابطه خاص بین یک موضوع و یک دسته‌بندی با استفاده از شناسه رکورد",
     *     operationId="showTopicCategory",
     *     tags={"Admin Topic-Categories"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ارتباط موضوع و دسته‌بندی",
     *         required=true,
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(ref="#/components/schemas/TopicCategoryIndexResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="رکورد یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ارتباط موضوع و دسته‌بندی یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $topicCategory = $this->topicCategoryRepository->findById($id);
            
            if (!$topicCategory) {
                return $this->errorResponse('ارتباط موضوع و دسته‌بندی یافت نشد', 404);
            }

            $topicCategory = new TopicCategoryResource($topicCategory);
            $topicCategory->additional(['mode' => 'show']);

            return $this->successResponse(['topicCategory' => $topicCategory], 'جزئیات ارتباط موضوع و دسته‌بندی با موفقیت دریافت شد');
        } catch (\Exception $e) {
            return $this->errorResponse('مشکل در استخراج اطلاعات', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/topic-categories/{id}",
     *     summary="حذف ارتباط موضوع و دسته‌بندی",
     *     description="حذف رکورد از جدول topic_categories برای قطع ارتباط بین یک موضوع و دسته‌بندی",
     *     operationId="destroyTopicCategory",
     *     tags={"Admin Topic-Categories"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ارتباط موضوع و دسته‌بندی",
     *         required=true,
     *         @OA\Schema(type="integer", example=12)
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
     *                 example="ارتباط با موفقیت حذف شد"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="رکورد یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ارتباط موضوع و دسته‌بندی یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $topicCategory = $this->topicCategoryRepository->findById($id);
            
            if (!$topicCategory) {
                return $this->errorResponse('ارتباط موضوع و دسته‌بندی یافت نشد', 404);
            }

            $this->topicCategoryRepository->deleteTopicCategories($id);

            return $this->successResponse([], 'ارتباط با موفقیت حذف شد');
        } catch (\Exception $e) {
            return $this->errorResponse('مشکل در حذف ارتباط', 500);
        }
    }
}
