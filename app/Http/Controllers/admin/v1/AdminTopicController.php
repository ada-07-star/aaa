<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class AdminTopicController extends Controller
{
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
    public function index(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'sometimes|integer',
            'language_id' => 'sometimes|integer',
            'status' => 'sometimes|integer',
            'category_id' => 'sometimes|integer',
            'keyword' => 'sometimes|string',
            'sort_by' => 'sometimes|in:created_at,updated_at,title',
            'sort_direction' => 'sometimes|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100'
        ]);

        $query = Topic::with(['categories', 'language', 'department'])
            ->when($request->filled('keyword'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%');
            })
            ->when($request->filled('department_id'), function ($q) use ($request) {
                $departments = explode(',', $request->department_id);
                $q->whereIn('department_id', $departments);
            })
            ->when($request->filled('language_id'), function ($q) use ($request) {
                $q->where('language_id', $request->language_id);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $categories = explode(',', $request->category_id);
                $q->whereHas('categories', function ($subQ) use ($categories) {
                    $subQ->whereIn('categories.id', $categories);
                });
            });

        $sortBy = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $request->per_page ?? 10;
        $topics = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'لیست موضوعات با موفقیت بارگذاری شد',
            'code' => 200,
            'data' => [
                'topics' => $topics->map(function ($topic) {
                    return [
                        'id' => $topic->id,
                        'categories' => $topic->categories->map(function ($category) {
                            return [
                                'id' => $category->id,
                                'title' => $category->title,
                            ];
                        }),
                        'title' => $topic->title,
                        'current_state' => [
                            'title' => $topic->current_state,
                            'slug' => $topic->current_state,
                        ],
                        'submit_date_from' => $topic->submit_date_from ?? $topic->plan_date_from,
                        'submit_date_to' => $topic->submit_date_to ?? $topic->plan_date_to,
                        'language' => $topic->language ? [
                            'name' => $topic->language->name,
                            'id' => $topic->language->id,
                        ] : null,
                        'department' => [
                            'id' => $topic->department->id,
                            'title' => $topic->department->title,
                        ],
                    ];
                }),
                'pagination' => [
                    'total' => $topics->total(),
                    'current_page' => $topics->currentPage(),
                    'per_page' => $topics->perPage(),
                ],
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
