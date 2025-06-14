<?php

namespace App\Repositories;

use App\Interfaces\IdeaRatingRepositoryInterface;
use App\Models\IdeaRating;
use Illuminate\Http\Request;

/**
 * Class IdeaRatingRepository.
 */
class IdeaRatingRepository implements IdeaRatingRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    protected $model;
    public function __construct(IdeaRating $model)
    {
        $this->model = $model;
    }
    /**
     * Get all idea ratings with pagination.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function getAllIdeaRatings(Request $request)
    {
        $query = $this->model->where('idea_id', $request->input('idea_id'))->get();

        return $query;
    }

    /**
     * Find an idea rating by its ID.
     *
     * @param int $id
     */
    public function findById(string $id)
    {
        return $this->model->findOrFail($id);
    }
    /**
     * Create a new idea rating.
     *
     * @param array $data
     */
    public function createIdeaRating(array $data)
    {
        $existingRating = $this->model
            ->where('idea_id', $data['idea_id'])
            ->where('user_id', $data['user_id'])
            ->first();
        if ($existingRating) {
            $existingRating->update($data);
            return $existingRating;
        } else {
            return $this->model->create($data);
        }
    }

    /**
     * Update an existing idea rating.
     *
     * @param int $id
     * @param array $data
     */
    public function updateIdeaRating($id, $validated)
    {
        return$this->model
            ->where('id', $id)
            ->update(['rate_number' => $validated['rate_number']]);
    }
    /**
     * Delete an idea rating by its ID.
     *
     * @param int $id
     */
    public function deleteIdeaRating(string $id)
    {
        $rating = $this->findById($id);
        $rating->delete();
        return $rating;
    }
}
