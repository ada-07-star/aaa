<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Interfaces\TopicRepositoryInterface;
use Illuminate\Http\JsonResponse;

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

    /**
     * @OA\Get(
     *     path="/api/v1/app/topics",
     *     summary="Get list of topics",
     *     @OA\Response(
     *         response=200,
     *         description="List of topics"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $topics = $this->topicRepository->getTopicsList();

        return response()->json([
            'status' => 'success',
            'message' => 'با موفقیت انجام شد.',
            'data' => [
                'topics' => $topics,
            ],
        ]);
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
     *     summary="Get a specific topic",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Topic details"
     *     )
     * )
     */
    public function show($id)
    {
        $topic = $this->topicRepository->getTopicDetails($id);
        if (!$topic) {
            return response()->json([
                'status' => 'error',
                'message' => 'موضوع مورد نظر یافت نشد.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'جزئیات موضوع با موفقیت دریافت شد.',
            'data' => [
                'topic' => $topic,
            ],
        ]);
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
