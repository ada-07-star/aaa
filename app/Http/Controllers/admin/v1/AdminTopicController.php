<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicIndexRequest;
use App\Interfaces\TopicRepositoryInterface;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminTopicController extends Controller
{
    protected $topicRepository;

    /**
     * Constructor.
     *
     * @param TopicRepositoryInterface $topicRepository
     */
    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/admin/topics",
     *     tags={"Admin - Topics"},
     *     summary="لیست موضوعات",
     *     description="لیست تمامی موضوعات با امکان فیلتر بر اساس دپارتمان، زبان، وضعیت، دسته بندی و جستجوی کلیدواژه. امکان مرتب سازی بر اساس تاریخ ثبت وجود دارد.",
     *     operationId="adminTopicsList",
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="فیلتر بر اساس آیدی دپارتمان",
     *         required=false,
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="language_id",
     *         in="query",
     *         description="فیلتر بر اساس آیدی زبان",
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
     *         name="keyword",
     *         in="query",
     *         description="جستجو بر اساس کلیدواژه در عنوان",
     *         required=false,
     *         @OA\Schema(type="string", example="طرح")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="فیلتر بر اساس آیدی دسته بندی",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="فیلد برای مرتب سازی",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"created_at", "updated_at", "title"},
     *             default="created_at"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="جهت مرتب سازی",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"},
     *             default="desc"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="شماره صفحه",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="تعداد آیتم در هر صفحه",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="لیست موضوعات با موفقیت بارگذاری شد"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="topics",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=32323),
     *                         @OA\Property(
     *                             property="categories",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="title", type="string", example="موضوعات عام"),
     *                                 @OA\Property(property="id", type="integer", example=32323)
     *                             )
     *                         ),
     *                         @OA\Property(property="title", type="string", example="رواق ود"),
     *                         @OA\Property(
     *                             property="current_state",
     *                             type="object",
     *                             @OA\Property(property="title", type="string", example="ثبت ایده"),
     *                             @OA\Property(property="slug", type="string", example="add_idea")
     *                         ),
     *                         @OA\Property(property="submit_date_from", type="string", format="date-time", example="2025-04-28 14:08:04"),
     *                         @OA\Property(property="submit_date_to", type="string", format="date-time", example="2025-04-28 14:08:04"),
     *                         @OA\Property(
     *                             property="language",
     *                             type="object",
     *                             @OA\Property(property="title", type="string", example="فارسی"),
     *                             @OA\Property(property="id", type="string", example="22")
     *                         ),
     *                         @OA\Property(
     *                             property="department",
     *                             type="object",
     *                             @OA\Property(property="title", type="string", example="معاونت تبلیغات"),
     *                             @OA\Property(property="id", type="string", example="12")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="total", type="integer", example=15),
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=10)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="خطای درخواست",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="پارامترهای ارسالی معتبر نیستند")
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
    public function index(TopicIndexRequest $request)
    {
        $validated = $request->validated();
        $perPage = $request->per_page ?? 10;

        return $this->topicRepository->getAllFilteredTopics($validated, $perPage);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/topics",
     *     summary="Create a new topic",
     *     description="Endpoint for creating a new topic with all required fields",
     *     tags={"Admin - Topics"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "department_id", "language_id", "age_range", "current_state", "judge_number", "minimum_score", "status", "is_archive", "created_by", "submit_date_from"},
     *             @OA\Property(property="title", type="string", maxLength=500, example="عنوان موضوع نمونه"),
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="language_id", type="integer", example=1),
     *             @OA\Property(property="age_range", type="string", example="18-25"),
     *             @OA\Property(property="gender", type="integer", nullable=true, example=1),
     *             @OA\Property(property="thumb_image", type="string", maxLength=500, nullable=true, example="path/to/thumb.jpg"),
     *             @OA\Property(property="cover_image", type="string", maxLength=500, nullable=true, example="path/to/cover.jpg"),
     *             @OA\Property(property="submit_date_from", type="string", format="date-time", example="2023-01-01 00:00:00"),
     *             @OA\Property(property="submit_date_to", type="string", format="date-time", nullable=true, example="2023-12-31 23:59:59"),
     *             @OA\Property(property="consideration_date_from", type="string", format="date-time", nullable=true, example="2023-02-01 00:00:00"),
     *             @OA\Property(property="consideration_date_to", type="string", format="date-time", nullable=true, example="2023-02-28 23:59:59"),
     *             @OA\Property(property="plan_date_from", type="string", format="date-time", nullable=true, example="2023-03-01 00:00:00"),
     *             @OA\Property(property="plan_date_to", type="string", format="date-time", nullable=true, example="2023-03-31 23:59:59"),
     *             @OA\Property(property="current_state", type="string", maxLength=50, example="draft"),
     *             @OA\Property(property="judge_number", type="integer", example=3),
     *             @OA\Property(property="minimum_score", type="integer", example=50),
     *             @OA\Property(property="evaluation_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="is_archive", type="boolean", example=false),
     *             @OA\Property(property="created_by", type="integer", example=1),
     *             @OA\Property(property="updated_by", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Topic created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Topic created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="عنوان موضوع نمونه"),
     *                 @OA\Property(property="department_id", type="integer", example=1),
     *                 @OA\Property(property="current_state", type="string", example="draft"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // اعتبارسنجی داده‌های ورودی
        $validatedData = $request->validate([
            'title' => 'required|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'language_id' => 'required|exists:languages,id',
            'age_range' => 'required|string',
            'gender' => 'nullable|integer',
            'thumb_image' => 'nullable|string|max:500',
            'cover_image' => 'nullable|string|max:500',
            'submit_date_from' => 'required|date',
            'submit_date_to' => 'nullable|date|after_or_equal:submit_date_from',
            'consideration_date_from' => 'nullable|date|after_or_equal:submit_date_from',
            'consideration_date_to' => 'nullable|date|after_or_equal:consideration_date_from',
            'plan_date_from' => 'nullable|date',
            'plan_date_to' => 'nullable|date|after_or_equal:plan_date_from',
            'current_state' => 'required|string|max:50',
            'judge_number' => 'required|integer|min:1',
            'minimum_score' => 'required|integer|min:0',
            'evaluation_id' => 'nullable|exists:evaluations,id',
            'status' => 'required|boolean',
            'is_archive' => 'required|boolean',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            // ایجاد موضوع جدید
            $topic = Topic::create([
                'title' => $validatedData['title'],
                'department_id' => $validatedData['department_id'],
                'language_id' => $validatedData['language_id'],
                'age_range' => $validatedData['age_range'],
                'gender' => $validatedData['gender'] ?? null,
                'thumb_image' => $validatedData['thumb_image'] ?? null,
                'cover_image' => $validatedData['cover_image'] ?? null,
                'submit_date_from' => $validatedData['submit_date_from'],
                'submit_date_to' => $validatedData['submit_date_to'] ?? null,
                'consideration_date_from' => $validatedData['consideration_date_from'] ?? null,
                'consideration_date_to' => $validatedData['consideration_date_to'] ?? null,
                'plan_date_from' => $validatedData['plan_date_from'] ?? null,
                'plan_date_to' => $validatedData['plan_date_to'] ?? null,
                'current_state' => $validatedData['current_state'],
                'judge_number' => $validatedData['judge_number'],
                'minimum_score' => $validatedData['minimum_score'],
                'evaluation_id' => $validatedData['evaluation_id'] ?? null,
                'status' => $validatedData['status'],
                'is_archive' => $validatedData['is_archive'],
                'created_by' => $validatedData['created_by'],
                'updated_by' => $validatedData['updated_by']
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'موضوع با موفقیت ایجاد شد',
                'data' => [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'department_id' => $topic->department_id,
                    'current_state' => $topic->current_state,
                    'submit_date_from' => $topic->submit_date_from,
                    'created_at' => $topic->created_at->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
