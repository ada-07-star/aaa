<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Interfaces\EvaluationObjectRepositoryInterface;
use App\Http\Resources\Admin\EvaluationObjectResource;

class AdminEvaluationObjectController extends Controller
{
    use ApiResponse;
    protected $evaluationObjectRepository;

    public function __construct(EvaluationObjectRepositoryInterface $evaluationObjectRepository)
    {
        $this->evaluationObjectRepository = $evaluationObjectRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/evaluation-objects",
     *     summary="Get list of objects for a specific evaluation",
     *     description="Returns a paginated list of objects associated with an evaluation",
     *     operationId="getEvaluationObjects",
     *     tags={"Admin Evaluation-Objects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Evaluation objects fetched successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/EvaluationObjectResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=150)
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="evaluation_id",
     *         in="query",
     *         required=true,
     *         description="ID of the evaluation to filter objects by",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The evaluation_id field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "evaluation_id": {"The evaluation_id field is required."}
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
            $request->validate([
                'evaluation_id' => 'required|integer|exists:evaluations,id',
            ]);

            $evaluationObjects = $this->evaluationObjectRepository->getEvaluationObjects($request);
            if ($evaluationObjects->isEmpty()) {
                return $this->errorResponse('اطلاعات ارزشیابی مورد نیاز است', 400);
            }
           
            return $this->successResponse(
                EvaluationObjectResource::collection($evaluationObjects),
                'Evaluation objects fetched successfully'
            );
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
     *     path="/api/v1/admin/evaluation-objects",
     *     summary="افزودن گویه به ارزشیابی",
     *     description="ایجاد ارتباط بین یک گویه و یک ارزشیابی خاص، همراه با ترتیب قرارگیری.",
     *     operationId="addEvaluationObject",
     *     tags={"Admin Evaluation-Objects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"evaluation_id", "object_id", "order_of"},
     *             @OA\Property(property="evaluation_id", type="integer", example=5),
     *             @OA\Property(property="object_id", type="integer", example=3),
     *             @OA\Property(property="order_of", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="گویه با موفقیت به ارزشیابی اضافه شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=12)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 example={
     *                     "evaluation_id": {"The evaluation_id field is required."},
     *                     "object_id": {"The object_id field is required."},
     *                     "order_of": {"The order_of field is required."}
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
     *             @OA\Property(property="message", type="string", example="خطا در افزودن گویه به ارزشیابی")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'evaluation_id' => 'required|integer|exists:evaluations,id',
            'object_id'     => 'required|integer|exists:objects,id',
            'order_of'      => 'required|integer|min:1',
        ]);

        try {
            $evaluationObject = $this->evaluationObjectRepository->createEvaluationObject($validated);
            return $this->successResponse(
                ['id' => $evaluationObject->id],
                'گویه با موفقیت به ارزشیابی اضافه شد'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('خطا در افزودن گویه به ارزشیابی', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
     *     path="/api/v1/admin/evaluation-objects/{id}",
     *     summary="ویرایش ترتیب گویه در ارزشیابی",
     *     description="ویرایش مقدار order_of برای یک گویه در یک ارزشیابی خاص.",
     *     operationId="updateEvaluationObjectOrder",
     *     tags={"Admin Evaluation-Objects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID رکورد گویه-ارزشیابی",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_of"},
     *             @OA\Property(property="order_of", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ترتیب با موفقیت ویرایش شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="ترتیب با موفقیت ویرایش شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 example={
     *                     "order_of": {"The order_of field is required."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="خطا در ویرایش ترتیب گویه")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'order_of' => 'required|integer|min:1',
        ]);
        try {
            $result = $this->evaluationObjectRepository->updateEvaluationObjectOrder($id, $validated['order_of']);
            if (!$result) {
                return $this->errorResponse('یافت نشد', 404);
            }
            return $this->successResponse(null, 'ترتیب با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return $this->errorResponse('خطا در ویرایش ترتیب گویه', 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/evaluation-objects/{id}",
     *     summary="حذف گویه از ارزشیابی",
     *     description="حذف ارتباط بین یک گویه و یک ارزشیابی خاص.",
     *     operationId="deleteEvaluationObject",
     *     tags={"Admin Evaluation-Objects"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID رکورد گویه-ارزشیابی",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="گویه با موفقیت از ارزشیابی حذف شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="گویه با موفقیت از ارزشیابی حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="یافت نشد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="خطا در حذف گویه از ارزشیابی")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $result = $this->evaluationObjectRepository->deleteEvaluationObject($id);
            if (!$result) {
                return $this->errorResponse('یافت نشد', 404);
            }
            return $this->successResponse(null, 'گویه با موفقیت از ارزشیابی حذف شد');
        } catch (\Exception $e) {
            return $this->errorResponse('خطا در حذف گویه از ارزشیابی', 500);
        }
    }
}
