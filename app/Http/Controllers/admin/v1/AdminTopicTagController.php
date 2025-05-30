<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTopicTagRequest;
use App\Http\Resources\Admin\TopicTagResource;
use App\Interfaces\TopicTagRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminTopicTagController extends Controller
{
    use ApiResponse;

    protected $topicTagRepository;

    public function __construct(TopicTagRepositoryInterface $topicTagRepository)
    {
        $this->topicTagRepository = $topicTagRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/topic-tags",
     *     tags={"Admin - topic-tags"},
     *     summary="لیست ارتباط موضوع و تگ",
     *     description="نمایش لیستی از ارتباط‌های بین موضوعات و تگ‌ها با قابلیت فیلتر بر اساس topic_id یا tag_id",
     *     operationId="topicTagsList",
     *     @OA\Parameter(
     *         name="topic_id",
     *         in="query",
     *         description="فیلتر بر اساس آیدی موضوع",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="tag_id",
     *         in="query",
     *         description="فیلتر بر اساس آیدی تگ",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="topic_tags",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/TopicTagIndexResource")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطای داخلی سرور")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'topic_id' => $request->topic_id,
                'tag_id' => $request->tag_id
            ];

            $topicTags = $this->topicTagRepository->getAllFilteredTopicTags($filters);
            $topicTagsResource = TopicTagResource::collection($topicTags);

            return $this->successResponse(
                ['topic_tags' => $topicTagsResource],
                'لیست ارتباط موضوع و تگ با موفقیت دریافت شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/topic-tags",
     *     tags={"Admin - topic-tags"},
     *     summary="ایجاد ارتباط موضوع و تگ",
     *     description="ایجاد رکورد جدید در جدول واسط topic_tags جهت اتصال یک موضوع به یک تگ خاص",
     *     operationId="createTopicTag",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"topic_id", "tag_id"},
     *             @OA\Property(property="topic_id", type="integer", example=1),
     *             @OA\Property(property="tag_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=101)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطا در اعتبارسنجی داده‌ها"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="topic_id", type="array", @OA\Items(type="string", example="شناسه موضوع الزامی است")),
     *                 @OA\Property(property="tag_id", type="array", @OA\Items(type="string", example="شناسه تگ الزامی است"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطای داخلی سرور")
     *         )
     *     )
     * )
     */
    public function store(StoreTopicTagRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $id = $this->topicTagRepository->createTopicTag($validated);

            return $this->successResponse(
                ['topic_id' => $validated['topic_id']],
                'ارتباط موضوع و تگ با موفقیت ایجاد شد',
                201
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
} 