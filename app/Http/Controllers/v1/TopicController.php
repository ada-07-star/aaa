<?php

namespace App\Http\Controllers\v1;

use App\Http\Resources\Client\TopicResource as ClientTopicResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Interfaces\TopicRepositoryInterface;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TopicCollection;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;

class TopicController extends Controller
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


    public function updateUser()
    {
        User::where('id', 2)->update([
            'name' => 'ddd',
            'email' => 'davoodd@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        User::where('id', 1)->update([
            'name' => 'ali',
            'email' => 'davood@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/app/topics",
     *     summary="دریافت لیست موضوعات برای کاربران عادی",
     *     description="این endpoint لیست موضوعات فعال را با امکان فیلتر بر اساس دسته‌بندی بازمی‌گرداند.",
     *     tags={"Topics - User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="فیلتر بر اساس ID دسته‌بندی (اختیاری)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="شماره صفحه برای صفحه‌بندی (اختیاری)",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="تعداد آیتم‌ها در هر صفحه (اختیاری، حداکثر 20)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, maximum=20)
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
     *                     @OA\Items(ref="#/components/schemas/TopicResource")
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="total", type="integer", example=100),
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="last_page", type="integer", example=10)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="دسترسی ممنوع",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Forbidden.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $filters = ['status' => 1]; // پیش‌فرض برای کاربران عادی

        if ($request->has('category_id')) {
            $filters['category_id'] = $request->category_id;
        }

        $topics = $this->topicRepository->getAllFilteredTopics($filters);

        return new TopicCollection($topics);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
     *                 ref="#/components/schemas/ClientTopicResource"
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
            $topic =  $this->topicRepository->getTopicDetails($id);

            return (new ClientTopicResource($topic))
            ->additional([
                'success' => true,
                'message' => 'اطلاعات بخش با موفقیت دریافت شد.'
            ]);
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
