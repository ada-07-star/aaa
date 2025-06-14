<?php

namespace App\Http\Controllers\admin\v1;

use App\Interfaces\IdeaLogsRepositoryInterface;
use App\Http\Resources\Admin\IdeaLogResource;
use App\Http\Controllers\Controller;
use App\Models\IdeaLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AdminIdeaLogsController extends Controller
{
    use ApiResponse;
    protected $ideaLogRepository;

    public function __construct(IdeaLogsRepositoryInterface $ideaLogRepository)
    {
        $this->ideaLogRepository = $ideaLogRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/idea-logs",
     *     summary="لیست لاگ‌های ایده",
     *     description="نمایش لیست سوابق (لاگ‌ها) ثبت‌شده برای یک ایده خاص",
     *     tags={"Admin Idea-Logs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="idea_id",
     *         in="query",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="فیلد مرتب‌سازی",
     *         required=false,
     *         @OA\Schema(type="string", example="created_at")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="لاگ‌های ایده با موفقیت دریافت شدند"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="idea_logs",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="idea_id", type="integer", example=15),
     *                         @OA\Property(property="description", type="string", example="وضعیت به حالت 'در حال بررسی' تغییر یافت."),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-18T12:00:00Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطا در دریافت اطلاعات")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'idea_id' => $request->input('idea_id')
            ];
            $sort = $request->input('sort', 'created_at');

            $ideaLogs = $this->ideaLogRepository->getAllIdeaLogs($filters, $sort);
            $ideaLogs = IdeaLogResource::collection($ideaLogs);

            return $this->successResponse(
                ['idea_logs' => $ideaLogs],
                'لاگ‌های ایده با موفقیت دریافت شدند'
            );
        } catch (\Exception $e) {
            return exception_response_exception($request, $e);
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
     *     path="/api/v1/admin/idea-logs",
     *     summary="ثبت لاگ جدید برای یک ایده",
     *     description="ثبت یک لاگ یا توضیح درباره تغییری که روی یک ایده انجام شده است",
     *     tags={"Admin Idea-Logs"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات لاگ ایده",
     *         @OA\JsonContent(
     *             required={"idea_id", "description"},
     *             @OA\Property(property="idea_id", type="integer", example=15),
     *             @OA\Property(property="description", type="string", example="امتیاز نهایی توسط داوران ثبت شد.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="لاگ با موفقیت ثبت شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=12)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور داخلی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $log = IdeaLog::create([
                'idea_id' => $request->idea_id,
                'description' => $request->description
            ]);

            return response()->json([
                'status' => 'success',
                'data' => ['id' => $log->id]
            ], 201);
        } catch (\Exception $e) {
            return exception_response_exception($request, $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/idea-logs/{id}",
     *     summary="جزئیات یک لاگ ایده",
     *     description="مشاهده جزئیات یک لاگ ایده بر اساس شناسه آن",
     *     operationId="getIdeaLogById",
     *     tags={"Admin Idea-Logs"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the idea-log",
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
     *                     property="idea_log",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/IdeaLogShowResource")
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
            $log = $this->ideaLogRepository->findById($id);
            if (!$log) {
                return $this->errorResponse('لاگ مورد نظر یافت نشد', 404);
            }
            $ideaLogs = new IdeaLogResource($log);
            return $this->successResponse(
                ['ideaLogs' => $ideaLogs],
                'اطلاعات با موفقیت دریافت شدند'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
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
     *     path="/api/v1/admin/idea-logs/{id}",
     *     summary="حذف لاگ ایده",
     *     description="حذف یک لاگ ایده بر اساس شناسه آن",
     *     operationId="deleteIdeaLog",
     *     tags={"Admin Idea-Logs"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="شناسه لاگ ایده",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="لاگ با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="لاگی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="لاگی برای ایده یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="خطا در حذف لاگ")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {  try {
        $ideaRating = $this->ideaLogRepository->findById($id);
        if (!$ideaRating) {
            return $this->notFoundResponse('لاگی برای ایده یافت نشد', 404);
        }
        $this->ideaLogRepository->deleteIdeaLog($id);
        return $this->successResponse(null, 'لاگ با موفقیت حذف شد');
    } catch (\Exception $e) {
        return $this->errorResponse('خطا در حذف لاگی', 500);
    }
    }
}
