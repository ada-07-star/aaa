<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface IdeaRatingRepositoryInterface
{
    public function getAllIdeaRatings(Request $request);
    public function findById(string $id);
    public function createIdeaRating(array $data);
    public function updateIdeaRating($id, $validated);
    public function deleteIdeaRating(string $id);
}
