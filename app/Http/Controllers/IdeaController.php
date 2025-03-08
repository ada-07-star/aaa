<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\Repositories\IdeaRepository;
use Illuminate\Http\JsonResponse;

class IdeaController extends Controller
{
    protected $ideaRepository;

    public function __construct(IdeaRepository $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('sdfsdd');
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
    public function store(StoreIdeaRequest $request)
    {
        dd('sdfsd');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request): JsonResponse
    {
        $updatedIdea = $this->ideaRepository->updateIdea($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'ایده با موفقیت بروزرسانی شد.',
            'data' => [
                'idea' => $updatedIdea,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //
    }
}
