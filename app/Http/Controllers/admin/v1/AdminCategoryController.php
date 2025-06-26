<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminCategoryController extends Controller
{
    use ApiResponse;

    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/categories",
     *     tags={"Admin - categories"},
     *     summary="لیست دسته‌بندی‌ها",
     *     description="لیست دسته‌بندی‌ها با قابلیت فیلتر بر اساس شناسه مجموعه و مرتب‌سازی بر اساس زمان ایجاد",
     *     operationId="categoriesList",
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="فیلتر بر اساس آیدی مجموعه",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="فیلتر بر اساس وضعیت",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
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
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="categories",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/CategoryIndexResource")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="لیست دسته‌بندی‌ها با موفقیت دریافت شد")
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
                'department_id' => $request->department_id,
                'status' => $request->status,
                'sort' => $request->sort
            ];

            $categories = $this->categoryRepository->getAllFilteredCategories($filters);

            $categoriesResource = CategoryResource::collection($categories);
            $categoriesResource->additional(['mode' => 'index']);

            return $this->successResponse(
                ['categories' => $categoriesResource],
                'لیست دسته‌بندی‌ها با موفقیت دریافت شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
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
     *     path="/api/v1/admin/categories",
     *     tags={"Admin - categories"},
     *     summary="ایجاد دسته‌بندی جدید",
     *     description="ایجاد یک دسته‌بندی جدید در سیستم",
     *     operationId="storeCategory",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "title", "status", "created_by","updated_by", "department_id"},
     *             @OA\Property(property="name", type="string", example="دسته‌بندی جدید"),
     *             @OA\Property(property="title", type="string", example="دسته‌بندی جدید"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="created_by", type="integer", example=1),
     *             @OA\Property(property="updated_by", type="integer", example=1),
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="توضیحات دسته‌بندی", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="دسته‌بندی با موفقیت ایجاد شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="category", ref="#/components/schemas/CategoryStoreResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="دسته‌بندی با موفقیت ایجاد شد")
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
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="نام دسته‌بندی الزامی است"))
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
                'department_id' => 'required|exists:departments,id',
                'status' => 'required|integer|max:12',
                'created_by' => 'required|integer|max:12',
                'updated_by' => 'required|integer|max:12',
                'description' => 'nullable|string'
            ]);

            $category = $this->categoryRepository->createCategory($validated);
            $categoryArray = $this->categoryRepository->findById($category->id);
            $categoriesResource = new CategoryResource($categoryArray);
            $categoriesResource->additional(['mode' => 'store']);

            return $this->successResponse(
                ['category' => $categoriesResource],
                'دسته‌بندی با موفقیت ایجاد شد',
                201
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/categories/{id}",
     *     summary="مشاهده جزئیات دسته بندی",
     *     description="برگرداندن اطلاعات کامل یک دسته‌بندی بر اساس شناسه",
     *     tags={"Admin - categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه دسته‌بندی",
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
     *                 @OA\Property(property="category", ref="#/components/schemas/CategoryShowResource")
     *             ),
     *             @OA\Property(property="message", type="string", example="دسته‌بندی با موفقیت ایجاد شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="دسته‌بندی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="اطلاعات دسته‌بندی با موفقیت دریافت شد")
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
            $category = $this->categoryRepository->findById($id);

            if (!$category) {
                return $this->errorResponse('دسته‌بندی مورد نظر یافت نشد', 404);
            }

            $categoryResource = new CategoryResource($category);
            $categoryResource->additional(['mode' => 'show']);

            return $this->successResponse(
                ['category' => $categoryResource],
                'اطلاعات دسته‌بندی با موفقیت دریافت شد'
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
     *     path="/api/v1/admin/categories/{id}",
     *     summary="ویرایش دسته‌بندی",
     *     description="ویرایش اطلاعات یک رکورد دسته‌بندی",
     *     tags={"Admin - categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه دسته‌بندی",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "department_id", "status", "updated_by"},
     *             @OA\Property(property="title", type="string", maxLength=500, example="دسته‌بندی جدید"),
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="updated_by", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="توضیحات دسته‌بندی", nullable=true)
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
     *         description="دسته‌بندی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="دسته‌بندی مورد نظر یافت نشد")
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
            $category = $this->categoryRepository->findById($id);
            
            if (!$category) {
                return $this->errorResponse('دسته‌بندی مورد نظر یافت نشد', 404);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,id',
                'status' => 'required|boolean',
                'updated_by' => 'required|integer',
                'description' => 'nullable|string'
            ]);

            $this->categoryRepository->update($id, $validated);

            return $this->successResponse(
                ['id' => (int)$id],
                'دسته‌بندی با موفقیت بروزرسانی شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/categories/{id}",
     *     summary="حذف منطقی دسته‌بندی",
     *     description="حذف منطقی یک دسته‌بندی از طریق آرشیو کردن",
     *     tags={"Admin - categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه دسته‌بندی",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="دسته‌بندی با موفقیت غیرفعال شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="دسته‌بندی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="دسته‌بندی مورد نظر یافت نشد")
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
            $category = $this->categoryRepository->findById($id);
            
            if (!$category) {
                return $this->errorResponse('دسته‌بندی مورد نظر یافت نشد', 404);
            }

            $this->categoryRepository->update($id, ['status' => false]);

            return $this->successResponse(
                null,
                'دسته‌بندی با موفقیت غیرفعال شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
}
