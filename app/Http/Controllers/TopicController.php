<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Services\TopicService;
use GuzzleHttp\Psr7\Request;

class TopicController extends Controller
{
    protected $topicService;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::with('categories')->orderBy('created_at', 'asc')->get();

        $topicsList = $this->topicService->getTopicsList($topics);

        return response()->json([
            'status' => 'success',
            'message' => 'sample message',
            'data' => [
                'topics' => $topicsList,
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
        $topic = Topic::with(['categories'])->find($id);

        if (!$topic) {
            return response()->json([
                'status' => 'error',
                'message' => 'موضوع مورد نظر یافت نشد.',
            ], 404);
        }

        // دریافت اطلاعات موضوع با استفاده از TopicService
        $topicDetails = $this->topicService->getTopicDetails($topic);

        return response()->json([
            'status' => 'success',
            'message' => 'sample message',
            'data' => [
                'topic' => $topicDetails,
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
