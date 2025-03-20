<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\Interfaces\IdeaRepositoryInterface;
use Illuminate\Http\JsonResponse;

class IdeaController extends Controller
{
    protected $ideaRepository;

    public function __construct(IdeaRepositoryInterface $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(StoreIdeaRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $idea = $this->ideaRepository->createIdea($validatedData);

        $response = $this->ideaRepository->formatIdeaResponse($idea);

        return response()->json($response, 201);
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
    public function update(UpdateIdeaRequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //
    }
}
