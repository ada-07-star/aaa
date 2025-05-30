<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\IdeaResource;
use App\Interfaces\IdeaRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminIdeaController extends Controller
{
    use ApiResponse;
    protected $ideaRepository;

    public function __construct(IdeaRepositoryInterface $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/ideas",
     *     summary="لیست ایده‌ها",
     *     description="لیست ایده‌ها با قابلیت فیلتر بر اساس topic_id، current_state یا is_published",
     *     tags={"Admin - ideas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="topic_id",
     *         in="query",
     *         description="فیلتر بر اساس شناسه موضوع",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="current_state",
     *         in="query",
     *         description="فیلتر بر اساس وضعیت فعلی",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft", "active", "archived"})
     *     ),
     *     @OA\Parameter(
     *         name="is_published",
     *         in="query",
     *         description="فیلتر بر اساس وضعیت انتشار",
     *         required=false,
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
     *             @OA\Property(property="message", type="string", example="ایده‌ها با موفقیت دریافت شدند"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="ideas",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/IdeaIndexResource"),
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
                'topic_id' => $request->input('topic_id'),
                'current_state' => $request->input('current_state'),
                'is_published' => $request->input('is_published')
            ];
            $sort = $request->input('sort', 'created_at');

            $ideas = $this->ideaRepository->getAllIdeas($filters, $sort);
            $ideas = IdeaResource::collection($ideas);

            return $this->successResponse(
                ['ideas' => $ideas],
                'ایده‌ها با موفقیت دریافت شدند'
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
     *     path="/api/v1/admin/ideas",
     *     tags={"Admin - ideas"},
     *     summary="ایجاد ایده جدید",
     *     description="ایجاد یک ایده جدید در سیستم",
     *     operationId="storeIdea",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"topic_id", "title","description","is_published","current_state","participation_type","final_score","created_by"},
     *             @OA\Property(property="topic_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="عنوان ایده"),
     *             @OA\Property(property="description", type="string", example="توضیحات ایده"),
     *             @OA\Property(property="is_published", type="integer", example=1),
     *             @OA\Property(property="current_state", type="string", example="draft"),
     *             @OA\Property(property="participation_type", type="string", example="individual"),
     *             @OA\Property(property="final_score", type="integer", example=5, nullable=false),
     *             @OA\Property(property="created_by", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ایده با موفقیت ایجاد شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="idea", ref="#/components/schemas/IdeaStoreResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="ایده با موفقیت ایجاد شد")
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
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="نام ایده الزامی است"))
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
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'topic_id' => 'required|exists:topics,id',
                'is_published' => 'required|integer|max:12',
                'created_by' => 'required|integer|max:12',
                'final_score' => 'required|integer|min:0',
                'description' => 'nullable|string'
            ]);

            $idea = $this->ideaRepository->createIdea($validated);
            $ideaArray = $this->ideaRepository->findById($idea->id);
            $ideasResource = new IdeaResource($ideaArray);
            $ideasResource->additional(['mode' => 'store']);

            return $this->successResponse(
                ['idea' => $ideasResource],
                'ایده با موفقیت ایجاد شد',
                201
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/ideas/{id}",
     *     summary="مشاهده جزئیات ایده",
     *     description="برگرداندن اطلاعات کامل یک ایده بر اساس شناسه",
     *     tags={"Admin - ideas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="idea", ref="#/components/schemas/IdeaShowResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="ایده با موفقیت دریافت شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ایده یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="اطلاعات ایده با موفقیت دریافت شد")
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
    public function show(string $id)
    {
        try {
            $idea = $this->ideaRepository->findById($id);

            if (!$idea) {
                return $this->errorResponse('ایده مورد نظر یافت نشد', 404);
            }

            $ideaResource = new IdeaResource($idea);
            $ideaResource->additional(['mode' => 'show']);

            return $this->successResponse(
                ['idea' => $ideaResource],
                'اطلاعات ایده با موفقیت دریافت شد'
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
     * @OA\Put(
     *     path="/api/v1/admin/ideas/{id}",
     *     summary="ویرایش ایده",
     *     description="ویرایش اطلاعات یک رکورد ایده",
     *     tags={"Admin - ideas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "is_published", "current_state", "participation_type", "final_score"},
     *             @OA\Property(property="title", type="string", maxLength=500, example="ایده جدید"),
     *             @OA\Property(property="description", type="string", example="توضیحات ایده", nullable=true),
     *             @OA\Property(property="is_published", type="boolean", example=true),
     *             @OA\Property(property="current_state", type="string", example="draft"),
     *             @OA\Property(property="participation_type", type="string", example="individual"),
     *             @OA\Property(property="final_score", type="integer", example=100),
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
     *         response=404,
     *         description="ایده یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="ایده مورد نظر یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="داده‌های ارسالی معتبر نیستند")
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
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $idea = $this->ideaRepository->findById($id);

            if (!$idea) {
                return $this->errorResponse('ایده مورد نظر یافت نشد', 404);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_published' => 'required|boolean',
                'current_state' => 'required|string',
                'participation_type' => 'required|string',
                'final_score' => 'required|integer',
            ]);

            $this->ideaRepository->updateIdea($validated, $id);

            return $this->successResponse(
                ['id' => (int)$id],
                'ایده با موفقیت بروزرسانی شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/ideas/{id}",
     *     summary="حذف ایده",
     *     description="حذف  یک  ایده",
     *     tags={"Admin - ideas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="ایده با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ایده یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="ایده مورد نظر یافت نشد")
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
    public function destroy(string $id)
    {
        try {
            $this->ideaRepository->deleteIdea($id);
            return $this->successResponse(
                ['id' => (int)$id],
                'ایده با موفقیت حذف شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
}
