<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\IdeaRating;
use Illuminate\Http\Request;

class IdeaRatingController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/app/idea/{idea}/rate",
     *     summary="ثبت امتیاز برای یک ایده",
     *     description="کاربر نهایی می‌تواند به ایده‌ای امتیاز بدهد و آمار امتیازات را دریافت کند.",
     *     tags={"Ideas"},
     *     @OA\Parameter(
     *         name="idea",
     *         in="path",
     *         description="شناسه ایده",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rateNumber"},
     *             @OA\Property(property="rateNumber", type="integer", example=2, description="امتیاز ارائه شده (مثلاً از 1 تا 5)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="حالت موفق",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="totalRates", type="integer", example=10),
     *                 @OA\Property(property="personRate", type="integer", example=5),
     *                 @OA\Property(property="avgRate", type="number", format="float", example=3.5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="درخواست نادرست"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="عدم احراز هویت"
     *     )
     * )
     */
    public function rate(Request $request, Idea $idea)
    {
        $request->validate([
            'rateNumber' => 'required|integer|min:1|max:10',
        ]);
        
        $userId = auth()->id();
        $rateNumber = (int)$request->input('rateNumber');
        
        $rating = IdeaRating::updateOrCreate(
            ['user_id' => $userId, 'idea_id' => $idea->id],
            ['rate_number' => $rateNumber]
        );
        
        $totalRates = IdeaRating::where('idea_id', $idea->id)->count();
        $personRate = $rateNumber; // چون همین الان updateOrCreate کردیم
        $avgRate = IdeaRating::where('idea_id', $idea->id)->avg('rate_number');
        $roundedAvg = round($avgRate, 2);
        
        return response()->json([
            'data' => [
                'totalRates' => $totalRates,
                'personRate' => $personRate,
                'avgRate' => $roundedAvg,
            ],
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(IdeaRating $ideaRating)
    {
        //
    }

    public function update(Request $request, IdeaRating $ideaRating)
    {
        //
    }

    public function destroy(IdeaRating $ideaRating)
    {
        //
    }
}
