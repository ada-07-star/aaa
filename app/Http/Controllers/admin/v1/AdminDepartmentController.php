<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Interfaces\DepartmentRepositoryInterface;
use App\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminDepartmentController extends Controller
{
    protected $departmentRepo;

    public function __construct(DepartmentRepositoryInterface $departmentRepo)
    {
        $this->departmentRepo = $departmentRepo;
    }
    /**
     * نمایش لیست دپارتمان‌ها
     *
     * @OA\Get(
     *     path="/api/v1/admin/department",
     *     summary="دریافت لیست دپارتمان‌ها",
     *     description="این متد برای دریافت لیست تمام دپارتمان‌های سیستم استفاده می‌شود",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="تعداد رکوردها در هر صفحه (پیش‌فرض 10)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="شماره صفحه (پیش‌فرض 1)",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="فیلتر بر اساس وضعیت (true/false)",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="جستجو در عنوان و توضیحات",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="لیست دپارتمان‌ها با موفقیت دریافت شد"),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطای سرور داخلی")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $result = $this->departmentRepo->getAll($request);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطای سرور داخلی',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ایجاد یک دپارتمان جدید در سیستم
     *
     * @OA\Post(
     *     path="/api/v1/admin/department",
     *     summary="ایجاد دپارتمان جدید",
     *     description="این متد برای ایجاد یک دپارتمان جدید توسط کاربران با نقش ادمین استفاده می‌شود",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات دپارتمان جدید",
     *         @OA\JsonContent(
     *             required={"title","status","created_by"},
     *             @OA\Property(property="title", type="string", maxLength=500, example="حوزه فناوری اطلاعات"),
     *             @OA\Property(property="descriptions", type="string", example="مسئولیت‌های مربوط به توسعه نرم‌افزار"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="created_by", type="integer", format="int64", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="دپارتمان با موفقیت ایجاد شد"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در اعتبارسنجی داده‌ها"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="عنوان دپارتمان الزامی است")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="دسترسی غیرمجاز",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="شما مجوز ایجاد دپارتمان را ندارید")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطای سرور داخلی"),
     *             @OA\Property(property="error", type="string", example="خطای پایگاه داده")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'descriptions' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی داده‌ها',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $department = $this->departmentRepo->create(
                $request->all(),
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'بخش مورد نظر با موفقیت ثبت گردید.',
                'data' => $department->only([
                    'id',
                    'title',
                    'descriptions',
                    'created_by',
                    'updated_by'
                ])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد دپارتمان'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/department/{id}",
     *     summary="نمایش جزئیات یک بخش",
     *     description="نمایش اطلاعات کامل یک بخش بر اساس شناسه",
     *     tags={"Departments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه بخش",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="جزئیات بخش با موفقیت دریافت شد"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="بخش فناوری اطلاعات"),
     *                 @OA\Property(property="descriptions", type="string", example="توضیحات مربوط به بخش فناوری اطلاعات"),
     *                 @OA\Property(property="status", type="boolean", example=true),
     *                 @OA\Property(property="created_by", type="integer", example="5"),
     *                 @OA\Property(property="updated_by", type="integer", example="9"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="بخش یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="بخش مورد نظر یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در دریافت جزئیات بخش"),
     *             @OA\Property(property="error", type="string", example="متن خطا")
     *         )
     *     )
     * )
     */
    public function show($id, DepartmentRepository $departmentRepo)
    {
        try {
            $department = $this->departmentRepo->getDepartmentById($id);

            return response()->json([
                'success' => true,
                'message' => 'جزئیات بخش با موفقیت دریافت شد',
                'data' => $department,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بخش مورد نظر یافت نشد',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در بروزرسانی بخش',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * به‌روزرسانی اطلاعات دپارتمان
     *
     * @OA\Put(
     *     path="/api/v1/admin/department/{id}",
     *     summary="به‌روزرسانی دپارتمان",
     *     description="این متد برای به‌روزرسانی اطلاعات یک دپارتمان توسط کاربران با نقش ادمین استفاده می‌شود",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه دپارتمان",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات جدید دپارتمان",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", maxLength=500, example="عنوان جدید"),
     *             @OA\Property(property="descriptions", type="string", example="توضیحات جدید"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="دپارتمان با موفقیت به‌روزرسانی شد"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="توسعه نرم‌افزار"),
     *                 @OA\Property(property="descriptions", type="text", example="توسعه نرم‌افزار جهت بهبود"),
     *                 @OA\Property(property="created_by", type="integer", example="5"),
     *                 @OA\Property(property="updated_by", type="integer", example="9"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در اعتبارسنجی داده‌ها"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="عنوان دپارتمان الزامی است")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="دسترسی غیرمجاز",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="شما مجوز به‌روزرسانی دپارتمان را ندارید")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="دپارتمان یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="دپارتمان مورد نظر یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در به‌روزرسانی دپارتمان"),
     *             @OA\Property(property="error", type="string", example="پیغام خطا")
     *         )
     *     )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, DepartmentRepository $departmentRepo)
    {
        try {
            $department = $departmentRepo->updateDepartment(
                $id,
                $request->all(),
                Auth::id()
            );

            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'بخش مورد نظر یافت نشد',
                ], 400);
            }
            return response()->json([
                'success' => true,
                'message' => 'بخش با موفقیت بروزرسانی شد.',
                'data' => $department
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی داده‌ها',
                'errors' => $e->validator->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در بروزرسانی بخش',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف یک دپارتمان از سیستم
     *
     * @OA\Delete(
     *     path="/api/v1/admin/department/{id}",
     *     summary="حذف دپارتمان",
     *     description="این متد برای حذف یک دپارتمان توسط کاربران با نقش ادمین استفاده می‌شود",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه دپارتمان",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="دپارتمان با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="دسترسی غیرمجاز",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="شما مجوز حذف دپارتمان را ندارید")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="دپارتمان یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="دپارتمان مورد نظر یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در حذف دپارتمان"),
     *             @OA\Property(property="error", type="string", example="خطای پایگاه داده")
     *         )
     *     )
     * )
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->departmentRepo->softDelete($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'دپارتمان مورد نظر یافت نشد'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'دپارتمان با موفقیت حذف شد'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در حذف دپارتمان',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
