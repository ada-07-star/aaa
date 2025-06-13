<?php

namespace App\Http\Controllers\admin\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\ObjectRepositoryInterface;
use App\Http\Resources\Admin\ObjectResource;
use App\Traits\ApiResponse;
use Throwable;

class AdminObjectController extends Controller
{
    use ApiResponse;

    protected $ObjectRepository;

    public function __construct(ObjectRepositoryInterface $ObjectRepository)
    {
        $this->ObjectRepository = $ObjectRepository;
    }

     /**
     * @OA\Get(
     *     path="/api/v1/admin/objects",
     *     summary="Get list of evaluation objects",
     *     description="Returns a paginated list of objects",
     *     operationId="getObjects",
     *     tags={"Admin Object"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="evaluation_id",
     *         in="query",
     *         description="شناسه ارزشیابی",
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
     *                     property="Objects",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/ObjectIndexResource")
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
     *                 example="Failed to fetch evaluation objects"
     *             )
     *         )
     *     )
     * )
     */
   
    public function index(Request $request)
    {
        try {
            $filter = [
                'evaluation_id' => $request->evaluation_id,
            ];         
            
            $objects = $this->ObjectRepository->getAll($filter);
            $objectsResource = ObjectResource::collection($objects);
            $objectsResource->additional(['mode' => 'index']);

            return $this->successResponse(
                ['Objects' => $objectsResource],
                'اطلاعات با موفقیت دریافت شدند'
            ); } catch (Throwable $exception) {
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
