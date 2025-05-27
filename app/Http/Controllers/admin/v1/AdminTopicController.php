<?php

namespace App\Http\Controllers\Admin\v1;

use App\Interfaces\TopicRepositoryInterface;
use App\Http\Requests\TopicIndexRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Resources\TopicResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;


class AdminTopicController extends Controller
{
    use ApiResponse;
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
     *                         @OA\Property(property="submit_date_from", type="string", format="date-time", example="2025-06-10T23:59:59Z"),
     *                         @OA\Property(property="submit_date_to", type="string", format="date-time", example="2025-06-10T23:59:59Z"),
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
     *                 @OA\Property(property="language_id", type="integer", example=2),
     *                 @OA\Property(property="submit_date_from", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="submit_date_to", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *                 @OA\Property(property="current_state", type="boolean", example="false"),
     *                 @OA\Property(property="status", type="string", example="پیش نویس"),
     *                 @OA\Property(property="is_archive", type="boolean", example="false"),
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
    public function store(StoreTopicRequest $request)
    {
        // اعتبارسنجی داده‌های ورودی
        $validatedData = $request->validated();
        try {
            $topic = $this->topicRepository->create($validatedData);

            $topicArray = $this->topicRepository->findById($topic->id);

            return $this->successResponse(
                $topicArray,
                'ایده با موفقیت ثبت شد.',
                201
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/topics/{id}",
     *     summary="مشاهده جزئیات موضوع",
     *     description="برگرداندن اطلاعات کامل یک موضوع بر اساس شناسه",
     *     tags={"Admin - Topics"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه موضوع",
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
     *                 @OA\Property(property="id", type="integer", example=124),
     *                 @OA\Property(property="title", type="string", example="ایده‌های زیست‌محیطی"),
     *                 @OA\Property(property="department_id", type="integer", example=3),
     *                 @OA\Property(property="language_id", type="integer", example=1),
     *                 @OA\Property(property="age_range", type="string", example="teen"),
     *                 @OA\Property(property="gender", type="integer", example="1"),
     *                 @OA\Property(property="thumb_image", type="string", example="thumb.jpg"),
     *                 @OA\Property(property="cover_image", type="string", example="cover.jpg"),
     *                 @OA\Property(property="submit_date_from", type="string", format="date-time", example="2025-06-01T00:00:00Z"),
     *                 @OA\Property(property="submit_date_to", type="string", format="date-time", example="2025-06-10T23:59:59Z"),
     *                 @OA\Property(property="consideration_date_from", type="string", format="date-time", example="2025-06-11T00:00:00Z"),
     *                 @OA\Property(property="consideration_date_to", type="string", format="date-time", example="2025-06-20T23:59:59Z"),
     *                 @OA\Property(property="plan_date_from", type="string", format="date-time", example="2025-06-21T00:00:00Z"),
     *                 @OA\Property(property="plan_date_to", type="string", format="date-time", example="2025-06-30T23:59:59Z"),
     *                 @OA\Property(property="current_state", type="string", example="جدید"),
     *                 @OA\Property(property="judge_number", type="integer", example=3),
     *                 @OA\Property(property="minimum_score", type="integer", example=75),
     *                 @OA\Property(
     *                     property="Evaluation",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=6),
     *                     @OA\Property(property="title", type="string", example="سیستم ارزشیابی یک")
     *                 ),
     *                 @OA\Property(property="status", type="boolean", example=true),
     *                 @OA\Property(property="is_archive", type="boolean", example=false),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-18T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-18T12:00:00Z"),
     *                 @OA\Property(property="created_by", type="integer", example=101),
     *                 @OA\Property(property="updated_by", type="integer", example=101)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="موضوع یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="موضوع مورد نظر یافت نشد")
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
            $topic = $this->topicRepository->findById($id);

            if (!$topic) {
                return $this->errorResponse('موضوع مورد نظر یافت نشد', 404);
            }

            return $this->successResponse(
                new TopicResource($topic),
                'اطلاعات موضوع با موفقیت دریافت شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/topics/{id}",
     *     summary="ویرایش موضوع",
     *     description="ویرایش اطلاعات یک رکورد موضوع",
     *     tags={"Admin - Topics"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه موضوع",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "department_id", "language_id", "age_range", "current_state", "judge_number", "minimum_score", "status", "submit_date_from"},
     *             @OA\Property(property="title", type="string", maxLength=500, example="ایده‌های زیست‌محیطی"),
     *             @OA\Property(property="department_id", type="integer", example=3),
     *             @OA\Property(property="language_id", type="integer", example=1),
     *             @OA\Property(property="age_range", type="string", example="teen"),
     *             @OA\Property(property="is_archive", type="integer", example="0"),
     *             @OA\Property(property="gender", type="integer", example="1"),
     *             @OA\Property(property="thumb_image", type="string", maxLength=500, nullable=true, example="thumb.jpg"),
     *             @OA\Property(property="cover_image", type="string", maxLength=500, nullable=true, example="cover.jpg"),
     *             @OA\Property(property="submit_date_from", type="string", format="date-time", example="2025-02-12 16:27:01"),
     *             @OA\Property(property="submit_date_to", type="string", format="date-time", nullable=true, example="2025-02-12 16:27:01"),
     *             @OA\Property(property="consideration_date_from", type="string", format="date-time", nullable=true, example="2025-02-12 16:27:01"),
     *             @OA\Property(property="consideration_date_to", type="string", format="date-time", nullable=true, example="2025-02-12 16:27:01"),
     *             @OA\Property(property="plan_date_from", type="string", format="date-time", nullable=true, example="2025-02-12 16:27:01"),
     *             @OA\Property(property="plan_date_to", type="string", format="date-time", nullable=true, example="2025-02-12 16:27:01"),
     *             @OA\Property(property="current_state", type="string", maxLength=50, example="submission"),
     *             @OA\Property(property="judge_number", type="integer", example=3),
     *             @OA\Property(property="minimum_score", type="integer", example=75),
     *             @OA\Property(property="evaluation_id", type="integer", nullable=true, example=2),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="updated_by", type="integer", example=1)
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
     *                 @OA\Property(property="id", type="integer", example=124)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="موضوع یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="موضوع مورد نظر یافت نشد")
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
    public function update(StoreTopicRequest $request, string $id)
    {
        try {
            $topic = $this->topicRepository->findById($id);
            
            if (!$topic) {
                return $this->errorResponse('موضوع مورد نظر یافت نشد', 404);
            }
            $validatedData = $request->validated();

            $this->topicRepository->update($id, $validatedData);

            return $this->successResponse(
                ['id' => (int)$id],
                'موضوع با موفقیت بروزرسانی شد'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('داده‌های ارسالی معتبر نیستند', 422, $e->errors());
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/topics/{id}",
     *     summary="حذف منطقی موضوع",
     *     description="حذف منطقی یک موضوع از طریق آرشیو کردن",
     *     tags={"Admin - Topics"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه موضوع",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="موضوع با موفقیت آرشیو شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="موضوع یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="موضوع مورد نظر یافت نشد")
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
            $topic = $this->topicRepository->findById($id);
            
            if (!$topic) {
                return $this->errorResponse('موضوع مورد نظر یافت نشد', 404);
            }

            $this->topicRepository->update($id, ['is_archive' => true]);

            return $this->successResponse(
                null,
                'موضوع با موفقیت آرشیو شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
}
