<?php

namespace App\Http\Controllers\admin\v1;

use App\Interfaces\IdeaUserRepositoryInterface;
use App\Http\Resources\Admin\IdeaUserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class AdminIdeaUserController extends Controller
{
    use ApiResponse;
    protected $ideaUserRepository;

    public function __construct(IdeaUserRepositoryInterface $ideaUserRepository)
    {
        $this->ideaUserRepository = $ideaUserRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/idea-users",
     *     summary="گرفتن لیست کاربران ایده",
     *     description="برگرداند لیستی از کاربران مرتبط با یک ایده خاص",
     *     operationId="getIdeaUsers",
     *     tags={"Admin Idea-Users"},
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
     *                 @OA\Items(ref="#/components/schemas/IdeaUserIndexResource")
     *             ),
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="idea_id",
     *         in="query",
     *         required=true,
     *         description="ID of the idea to fetch users",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The idea_id field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "idea_id": {"The idea_id field is required."}
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
                'idea_id' => 'required|integer|exists:ideas,id',
            ]);
            $ideas = $this->ideaUserRepository->getIdeaUsers($request);
            return $this->successResponse(IdeaUserResource::collection($ideas), 'Ideas fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('مشکل در استخراج اطلاعات', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/idea-users",
     *     summary="افزودن کاربر به ایده",
     *     description="Adds a new user to the list of idea participants",
     *     operationId="storeIdeaUser",
     *     tags={"Admin Idea-Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"idea_id", "user_id"},
     *             @OA\Property(property="idea_id", type="integer", example=6),
     *             @OA\Property(property="user_id", type="integer", example=2)
     *         )
     *     ),
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
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/IdeaUserIndexResource"
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
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid")
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
    public function store(Request $request): JsonResponse
    {
        try {
            $ideaUser = $this->ideaUserRepository->createIdeaUser($request->only(['idea_id', 'user_id']));
            return $this->successResponse(['id' => $ideaUser->id], 'User added to idea successfully');
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/idea-users/{id}",
     *     summary="گرفتن جزئیات یک رابط ایده و کاربر",
     *     description="Returns details of a specific idea-user relationship",
     *     operationId="getIdeaUser",
     *     tags={"Admin Idea-Users"},
     *     security={{"bearerAuth": {}}},
     *     
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the idea-user relationship",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=3
     *         )
     *     ),
     *     
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
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/IdeaUserShowResource"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Idea user fetched successfully"
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Relationship not found"
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated"
     *             )
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $idea = $this->ideaUserRepository->findById($id);

            if (!$idea) {
                return $this->errorResponse('ایده مورد نظر یافت نشد', 404);
            }

            $ideaUserResource = new IdeaUserResource($idea);
            $ideaUserResource->additional(['mode' => 'show']);

            return $this->successResponse(
                ['idea' => $ideaUserResource],
                'اطلاعات ایده و کاربر با موفقیت دریافت شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/idea-users/{id}",
     *     summary="حذف یک رابط ایده و کاربر",
     *     description="حذف  یک رابط ایده و کاربر",
     *     tags={"Admin Idea-Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="عملیات موفق",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="رابط کاربر و ایده با موفقیت حذف شد")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="ایده یافت نشد",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="رابط کاربر و ایده مورد نظر یافت نشد")
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
            $recordExists = $this->ideaUserRepository->findById($id);
            if (!$recordExists) {
                return $this->errorResponse('رابط کاربر و ایده مورد نظر یافت نشد', 404);
            }

            $this->ideaUserRepository->deleteIdeaUser($id);

            return $this->successResponse(
                ['id' => $id],
                'رکورد ارتباطی کاربر و ایده با موفقیت حذف شد'
            );
        } catch (\Exception $e) {
            return exception_response_exception(request(), $e);
        }
    }
}
