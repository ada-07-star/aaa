<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminDepartmentController extends Controller
{
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
            // ایجاد کوئری پایه
            $query = Department::query();

            // اعمال فیلتر وضعیت
            if ($request->has('status')) {
                $query->where('status', $request->boolean('status'));
            }

            // اعمال جستجو
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('descriptions', 'like', "%{$search}%");
                });
            }

            // مرتب‌سازی پیش‌فرض
            $query->orderBy('created_at', 'desc');

            // صفحه‌بندی نتایج
            $perPage = $request->input('per_page', 10);
            $departments = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'لیست دپارتمان‌ها با موفقیت دریافت شد',
                'data' => $departments->items(),
                'meta' => [
                    'current_page' => $departments->currentPage(),
                    'per_page' => $departments->perPage(),
                    'total' => $departments->total(),
                ]
            ]);
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
        // ایجاد دپارتمان جدید
        $department = Department::create([
            'title' => $request->title,
            'descriptions' => $request->descriptions,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => 'true',
            'message' => 'بخش مورد نظر با موفقیت ثبت گردید.',
            'data' =>  [
                'id' => $department->id,
                'title' => $department->title,
                'descriptions' => $department->descriptions,
                'created_by' => $department->created_by,
                'updated_by' => $department->updated_by,
            ],
        ]);
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
     *                 @OA\Property(property="created_by", type="integer", example="1"),
     *                 @OA\Property(property="updated_by", type="integer", example="1"),
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
    public function update(Request $request, $id)
    {
        try {
            $department = Department::find($id);

            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'بخش مورد نظر یافت نشد',
                ], 400);
            }
                $validator = Validator::make($request->all(), [
                    'title' => 'sometimes|string|max:500',
                    'descriptions' => 'nullable|string',
                    'status' => 'sometimes|boolean',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'خطا در اعتبارسنجی داده‌ها',
                        'errors' => $validator->errors()
                    ], 400);
                }

                $department->update([
                    'title' => $request->input('title', $department->title),
                    'descriptions' => $request->input('descriptions', $department->descriptions),
                    'status' => $request->input('status', $department->status),
                    'updated_by' => Auth::id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'بخش با موفقیت بروزرسانی شد.',
                    'data' => $department
                ], 200);
            
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
            $department = Department::find($id);

            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'دپارتمان مورد نظر یافت نشد'
                ], 404);
            }

            $department->delete();

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
