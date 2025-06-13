<?php

namespace App\Http\Controllers\admin\v1;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Interfaces\EvaluationRepositoryInterface;
use App\Http\Resources\Admin\EvaluationResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Throwable;

class AdminEvaluationController extends Controller
{

    use ApiResponse;

    protected $evaluationRepository;

    public function __construct(EvaluationRepositoryInterface $evaluationRepository)
    {
        $this->evaluationRepository = $evaluationRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/evaluations",
     *     summary="Get list of evaluations",
     *     description="Returns a paginated list of evaluations",
     *     operationId="getEvaluations",
     *     tags={"Admin Evaluations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="شناسه دپارتمان",
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
     *                     property="evaluations",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/EvaluationIndexResource")
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
     *                 example="Failed to fetch evaluations"
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $filters = [
                'department_id' => $request->department_id,
                'status' => $request->status,
            ];
            $evaluations = $this->evaluationRepository->getAll($filters);
            if ($evaluations->isEmpty()) {
                return $this->errorResponse('ارزیابی مورد نظر دریافت نشد', 404);
            }
            $evaluationsResource = EvaluationResource::collection($evaluations);
            $evaluationsResource->additional(['mode' => 'index']);

            return $this->successResponse(
                ['evaluations' => $evaluationsResource],
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
     *     path="/api/v1/admin/evaluations",
     *     summary="ایجاد ارزشیابی جدید",
     *     description="این متد برای ایجاد یک ارزشیابی جدید استفاده می‌شود",
     *     tags={"Admin Evaluations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات ارزشیابی جدید",
     *         @OA\JsonContent(
     *             required={"title","department_id","status","created_by"},
     *             @OA\Property(property="title", type="string", example="ارزشیابی برنامه‌های خلاقانه"),
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="توضیحات کامل درباره این ارزشیابی"),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="created_by", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="ارزشیابی با موفقیت ثبت شد"),
     *             @OA\Property(
     *                 property="data",
     *                  type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
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
     *                 @OA\Property(property="title", type="string", example="عنوان ارزشیابی الزامی است")
     *             )
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
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'created_by' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی داده‌ها',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $evaluation = $this->evaluationRepository->create($request->all(), $request->user()->id);

            return $this->successResponse(
                ['id' => $evaluation->id],
                'ارزشیابی با موفقیت ایجاد شد',
                201
            );
        } catch (Throwable $exception) {
            return exception_response_exception($request, $exception);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/evaluations/{id}",
     *     summary="نمایش جزئیات یک  ارزشیابی",
     *     description="نمایش اطلاعات کامل یک  ارزشیابی بر اساس شناسه",
     *     tags={"Admin Evaluations"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ارزشیابی",
     *         required=true,
     *         @OA\Schema(type="integer")
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
     *                     property="evaluations",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/EvaluationShowResource")
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
     *         response=404,
     *         description="ارزشیابی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="ارزشیابی مورد نظر یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در دریافت جزئیات  ارزشیابی"),
     *             @OA\Property(property="error", type="string", example="متن خطا")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $evaluation = $this->evaluationRepository->find($id);
            $evaluationResource = new EvaluationResource($evaluation);
            $evaluationResource->additional(['mode' => 'show']);
            return $this->successResponse(
                ['evaluations' => $evaluationResource],
                'جزئیات ارزشیابی با موفقیت دریافت شد'
            );
        } catch (ModelNotFoundException $e) {
            return exception_response_exception(request(), $e);
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
     * به‌روزرسانی اطلاعات دپارتمان
     *
     * @OA\Put(
     *     path="/api/v1/admin/evaluations/{id}",
     *     summary="به‌روزرسانی ارزشیابی",
     *     description="این متد برای به‌روزرسانی اطلاعات یک  ارزشیابی توسط کاربران با نقش ادمین استفاده می‌شود",
     *     tags={"Admin Evaluations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ارزشیابی",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="اطلاعات جدید ارزشیابی",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", maxLength=500, example="عنوان جدید"),
     *             @OA\Property(property="description", type="string", example="توضیحات جدید"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="ارزشیابی با موفقیت به‌روزرسانی شد"),
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
     *                 @OA\Property(property="title", type="string", example="عنوان ارزشیابی الزامی است")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="دسترسی غیرمجاز",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="شما مجوز به‌روزرسانی ارزشیابی را ندارید")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ارزشیابی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="ارزشیابی مورد نظر یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="خطا در به‌روزرسانی ارزشیابی"),
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
            $this->evaluationRepository->update(
                $id,
                array_merge($request->all(), ['updated_by' => Auth::id()])
            );

            return $this->successResponse(
                null,
                'ارزشیابی با موفقیت بروزرسانی شد.'
            );
        } catch (ModelNotFoundException $e) {
            return exception_response_exception(request(), $e);
        } catch (ValidationException $e) {
            return exception_response_exception(request(), $e);
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/evaluations/{id}",
     *     summary="حذف ارزشیابی",
     *     description="حذف  یک  ارزشیابی",
     *     tags={"Admin Evaluations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ارزشیابی",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="ارزشیابی با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ارزشیابی یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="ارزشیابی مورد نظر یافت نشد")
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
            $this->evaluationRepository->delete($id);
            return $this->successResponse(
                null,
                'ارزشیابی با موفقیت حذف شد.'
            );
        } catch (ModelNotFoundException $e) {
            return exception_response_exception(request(), $e);
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
}
