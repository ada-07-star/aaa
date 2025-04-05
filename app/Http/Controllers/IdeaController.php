<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\Interfaces\IdeaRepositoryInterface;
use Illuminate\Http\JsonResponse;

class IdeaController extends Controller
{
    protected $ideaRepository;

    public function __construct(IdeaRepositoryInterface $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(StoreIdeaRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $idea = $this->ideaRepository->createIdea($validatedData);

        $response = $this->ideaRepository->formatIdeaResponse($idea);

        return response()->json($response, 201);
    }

        /**
     * @OA\Get(
     *     path="/api/v1/app/idea/{id}",
     *     tags={"Ideas"},
     *     summary="نمایش جزئیات ایده",
     *     description="نمایش کامل اطلاعات یک ایده بر اساس شناسه",
     *     operationId="showIdea",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="شناسه یکتای ایده",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="جزئیات موضوع با موفقیت دریافت شد."),
     *            
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ایده یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="موضوع مورد نظر یافت نشد.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $results = $this->ideaRepository->showIdeaRepository($id);
        if (!$results) {
            return response()->json([
                'status' => 'error',
                'message' => 'موضوع مورد نظر یافت نشد.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'جزئیات موضوع با موفقیت دریافت شد.',
            'data' => [
                'results' => $results,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/v1/app/idea/{ideaId}",
     *     tags={"Idea"},
     *     summary="به‌روزرسانی ایده",
     *     description="این endpoint برای به‌روزرسانی عنوان ایده با شناسه مشخص استفاده می‌شود.",
     *     operationId="updateIdea",
     *     @OA\Parameter(
     *         name="ideaId",
     *         in="path",
     *         required=true,
     *         description="شناسه یکتای ایده",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="داده‌های مورد نیاز برای به‌روزرسانی ایده",
     *         @OA\JsonContent(
     *             required={"title", "description", "participation_type"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="عنوان جدید ایده",
     *                 maxLength=255,
     *                 description="عنوان ایده (حداکثر 255 کاراکتر)"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="توضیحات کامل درباره ایده جدید",
     *                 description="محتوای کامل توضیحات ایده"
     *             ),
     *             @OA\Property(
     *                 property="participation_type",
     *                 type="string",
     *                 enum={"individual", "team"},
     *                 example="individual",
     *                 description="نوع مشارکت در ایده"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *        @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="ایده با موفقیت به‌روزرسانی شد."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="idea",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="عنوان جدید ایده"),
     *                     @OA\Property(property="description", type="string", example="توضیحات کامل"),
     *                     @OA\Property(
     *                         property="participation_type",
     *                         type="string",
     *                         example="individual",
     *                         description="نوع مشارکت: team یا individual"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         format="date-time",
     *                         example="2023-05-01T12:00:00Z"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="ورودی نامعتبر",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ایده پیدا نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Idea not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateIdeaRequest $request, $ideaId)
    {
        // $idea = Idea::findOrFail($ideaId);

        // if ($ideaId == Auth::user()->id) {

        $ideaUpdate = $this->ideaRepository->updateIdea($request->all(), $ideaId);

        $results = $this->ideaRepository->formatUpdateIdea($ideaUpdate);

        return response()->json($results, 200);
        // } else {
        //     throw new \Exception('شما مجاز به ویرایش این ایده نیستید.', 403);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //
    }
}
