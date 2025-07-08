<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TopicJudgeResource;
use App\Interfaces\TopicJudgeRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminTopicJudgeController extends Controller
{
    use ApiResponse;

    protected $topicJudgeRepository;

    public function __construct(TopicJudgeRepositoryInterface $topicJudgeRepository)
    {
        $this->topicJudgeRepository = $topicJudgeRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/topic-judges",
     *     summary="لیست داوران اختصاص‌یافته به یک موضوع",
     *     description="این سرویس برای دریافت لیست کاربران (داوران) اختصاص‌یافته به یک موضوع خاص است، به همراه اطلاعات تکمیلی مانند آخرین صفحه دیده‌شده. مناسب برای پنل ادمین.",
     *     tags={"Admin - Topic Judges"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="topic_id",
     *         in="query",
     *         description="شناسه موضوع برای دریافت لیست داوران",
     *         required=true,
     *         @OA\Schema(type="integer", example=123)
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
     *                     property="topic_judges",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="user_id", type="integer", example=45),
     *                         @OA\Property(property="topic_id", type="string", example="123"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-15T08:00:00Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-15T08:00:00Z"),
     *                         @OA\Property(
     *                             property="user",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=45),
     *                             @OA\Property(property="name", type="string", example="علی احمدی"),
     *                             @OA\Property(property="email", type="string", example="ali@example.com")
     *                         ),
     *                         @OA\Property(
     *                             property="topic",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=123),
     *                             @OA\Property(property="title", type="string", example="موضوع نمونه")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="لیست داوران موضوع با موفقیت دریافت شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="درخواست نامعتبر",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="پارامتر topic_id الزامی است")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
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
            // Validate required topic_id parameter
            if (!$request->has('topic_id')) {
                return $this->errorResponse(
                    'پارامتر topic_id الزامی است',
                    400
                );
            }

            $topicId = (int) $request->get('topic_id');

            if ($topicId <= 0) {
                return $this->errorResponse(
                    'topic_id باید یک عدد صحیح مثبت باشد',
                    400
                );
            }

            // Get judges assigned to the topic
            $topicJudges = $this->topicJudgeRepository->getJudgesByTopicId($topicId);

            // Transform the data using the resource
            $topicJudgesResource = TopicJudgeResource::collection($topicJudges);

            return $this->successResponse(
                ['topic_judges' => $topicJudgesResource],
                'لیست داوران موضوع با موفقیت دریافت شد',
                200
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'خطای داخلی سرور: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage (for assigning judges).
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'topic_id' => 'required|integer|exists:topics,id',
            ]);

            $userId = (int) $request->get('user_id');
            $topicId = (int) $request->get('topic_id');

            // Check if judge is already assigned
            if ($this->topicJudgeRepository->isJudgeAssignedToTopic($userId, $topicId)) {
                return $this->errorResponse(
                    'این داور قبلاً به این موضوع اختصاص داده شده است',
                    409
                );
            }

            // Assign judge to topic
            $topicJudge = $this->topicJudgeRepository->assignJudgeToTopic($userId, $topicId);

            // Load relationships for response
            $topicJudge->load(['user:id,name,email', 'topic:id,title']);

            return $this->successResponse(
                ['topic_judge' => new TopicJudgeResource($topicJudge)],
                'داور با موفقیت به موضوع اختصاص داده شد',
                201
            );

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                'اطلاعات ارسالی نامعتبر است',
                422,
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'خطای داخلی سرور: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage (for removing judge assignment).
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'topic_id' => 'required|integer|exists:topics,id',
            ]);

            $userId = (int) $request->get('user_id');
            $topicId = (int) $request->get('topic_id');

            // Check if judge is assigned to topic
            if (!$this->topicJudgeRepository->isJudgeAssignedToTopic($userId, $topicId)) {
                return $this->errorResponse(
                    'این داور به این موضوع اختصاص داده نشده است',
                    404
                );
            }

            // Remove judge from topic
            $result = $this->topicJudgeRepository->removeJudgeFromTopic($userId, $topicId);

            if ($result) {
                return $this->successResponse(
                    [],
                    'داور با موفقیت از موضوع حذف شد',
                    200
                );
            } else {
                return $this->errorResponse(
                    'خطا در حذف داور از موضوع',
                    500
                );
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                'اطلاعات ارسالی نامعتبر است',
                422,
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'خطای داخلی سرور: ' . $e->getMessage(),
                500
            );
        }
    }
}