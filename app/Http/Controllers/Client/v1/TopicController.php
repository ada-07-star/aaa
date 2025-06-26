<?php

namespace App\Http\Controllers\Client\v1;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Interfaces\TopicRepositoryInterface;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\TopicResource;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Topic;
use App\Models\User;

class TopicController extends Controller
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


    public function updateUser()
    {
        User::where('id', 2)->update([
            'name' => 'david',
            'email' => 'davoodd@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        User::where('id', 1)->update([
            'name' => 'davood',
            'email' => 'davood@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/app/topics",
     *     summary="دریافت لیست موضوعات برای کاربران عادی",
     *     description="این لیست موضوعات فعال را با امکان فیلتر بر اساس دسته‌بندی، صفحه‌بندی و مرتب‌سازی بازمی‌گرداند.",
     *     tags={"Topics - User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="فیلتر بر اساس دپارتمان",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="جستجو در عنوان موضوعات",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         description="فیلد برای مرتب سازی (پیش فرض: created_at)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"created_at", "title", "updated_at"})
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="جهت مرتب سازی (پیش فرض: asc)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="تعداد آیتم ها در هر صفحه (پیش فرض: 10)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TopicResource")),
     *             @OA\Property(property="message", type="string", example="لیست موضوعات با موفقیت دریافت شد."),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="خطای سرور",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="خطای سرور"),
     *             @OA\Property(property="status", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $filters = ['status' => 1];

            if ($request->has('department_id')) {
                $filters['department_id'] = $request->department_id;
            }
            if ($request->has('keyword')) {
                $filters['keyword'] = $request->keyword;
            }
            $perPage = $request->per_page ?? 10;

            $topics = $this->topicRepository->getAllTopicsUsers($filters, $perPage);

            $topicsResource = TopicResource::collection($topics);
            $topicsResource->additional(['mode' => 'client_index']);

            return $this->successResponse(
                ['topics' => $topicsResource],
                'لیست موضوعات با موفقیت دریافت شد.',
                200
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/v1/app/topics/{id}",
     *     summary="Get single topic details",
     *     description="Retrieves detailed information about a specific topic",
     *     operationId="getTopicById",
     *     tags={"Topics - User"},
     *     security={{"bearerAuth": {}}},
     *     
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of topic to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="جزئیات موضوع با موفقیت دریافت شد"),
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/TopicResource"
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Topic not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Topic not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Server Error"),
     *             @OA\Property(property="exception", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $topic =  $this->topicRepository->findById($id);
            $topicsResource = new TopicResource($topic);
            $topicsResource->additional(['mode' => 'client_index']);
            return $this->successResponse(
                ['topics' => $topicsResource],
                'لیست موضوعات با موفقیت دریافت شد.',
                200
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
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        //
    }
}
