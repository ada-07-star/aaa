<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Repositories\TopicRepository;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    protected $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $topics = $this->topicRepository->getTopicsList();

        return response()->json([
            'status' => 'success',
            'message' => 'sample message',
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
     * Display the specified resource.
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
