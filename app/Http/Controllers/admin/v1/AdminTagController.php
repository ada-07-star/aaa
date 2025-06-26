<?php

namespace App\Http\Controllers\admin\v1;

use App\Interfaces\TagRepositoryInterface;
use App\Http\Resources\Admin\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AdminTagController extends Controller
{
    use ApiResponse;
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/tags",
     *     summary="لیست تگ‌ها",
     *     description="این متد برای دریافت لیست تمام تگ‌های سیستم استفاده می‌شود",
     *     tags={"Admin - tags"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="جستجو بر اساس عنوان تگ",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *             @OA\Property(property="message", type="string", example="تگ‌ها با موفقیت دریافت شدند"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/TagIndexResource")
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
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search')
        ];
        $sort = $request->input('sort', 'created_at');
        $tags = $this->tagRepository->getAllTags($filters, $sort);
        $tags = TagResource::collection($tags);

        return $this->successResponse($tags, 'تگ‌ها با موفقیت دریافت شدند');
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
     *     path="/api/v1/admin/tags",
     *     tags={"Admin - tags"},
     *     summary="ایجاد تگ جدید",
     *     description="ایجاد یک تگ جدید در سیستم",
     *     operationId="storeTag",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="تگ جدید"),
     *             @OA\Property(property="description", type="string", example="توضیحات تگ", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="تگ با موفقیت ایجاد شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="tag", ref="#/components/schemas/TagStoreResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="تگ با موفقیت ایجاد شد")
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
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="عنوان تگ الزامی است"))
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
                'description' => 'required|string',
            ]);

            $tags = $this->tagRepository->createTag($validated);
            $tagsArray = $this->tagRepository->getTagById($tags->id);
            $tagsResource = new TagResource($tagsArray);
            $tagsResource->additional(['mode' => 'store']);

            return $this->successResponse(
                ['tag' => $tagsResource],
                'تگ با موفقیت ایجاد شد',
                201
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

  /**
     * @OA\Get(
     *     path="/api/v1/admin/tags/{id}",
     *     summary="مشاهده جزئیات تگ",
     *     description="برگرداندن اطلاعات کامل یک تگ بر اساس شناسه",
     *     tags={"Admin - tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه تگ",
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
     *                 @OA\Property(property="tag", ref="#/components/schemas/TagShowResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="تگ با موفقیت دریافت شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="تگ یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="تگ مورد نظر یافت نشد")
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
            $tag = $this->tagRepository->getTagById($id);

            if (!$tag) {
                return $this->errorResponse('تگ مورد نظر یافت نشد', 404);
            }

            $tagResource = new TagResource($tag);
            $tagResource->additional(['mode' => 'show']);

            return $this->successResponse(
                ['tag' => $tagResource],
                'اطلاعات تگ با موفقیت دریافت شد'
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
     *     path="/api/v1/admin/tags/{id}",
     *     summary="ویرایش تگ",
     *     description="ویرایش اطلاعات یک رکورد تگ",
     *     tags={"Admin - tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه تگ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", maxLength=500, example="تگ جدید"),
     *             @OA\Property(property="description", type="string", example="توضیحات تگ", nullable=true),
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
     *         description="تگ یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="تگ مورد نظر یافت نشد")
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
            $tag = $this->tagRepository->getTagById($id);
            
            if (!$tag) {
                return $this->errorResponse('تگ مورد نظر یافت نشد', 404);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $this->tagRepository->updateTag($id, $validated);

            return $this->successResponse(
                ['id' => (int)$id],
                'تگ با موفقیت بروزرسانی شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

/**
     * @OA\Delete(
     *     path="/api/v1/admin/tags/{id}",
     *     summary="حذف تگ",
     *     description="حذف  یک تگ",
     *     tags={"Admin - tags"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه تگ",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="تگ با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="تگ یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="تگ مورد نظر یافت نشد")
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
            $this->tagRepository->deleteTag($id);
            return $this->successResponse(
                ['id' => (int)$id],
                'تگ با موفقیت حذف شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
}
