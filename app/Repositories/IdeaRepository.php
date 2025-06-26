<?php

namespace App\Repositories;

use App\Interfaces\IdeaRepositoryInterface;
use App\Models\Idea;
use App\Models\IdeaUser;
use Illuminate\Support\Facades\Auth;

class IdeaRepository implements IdeaRepositoryInterface
{
    protected $model;

    public function __construct(Idea $model)
    {
        $this->model = $model;
    }

    public function getPublishedIdeasForTopic($topic)
    {

        $query = $this->model->query();

        return $query->where('topic_id', $topic)->where('is_published', 1)->get();
    }

    public function getAllIdeas(array $filters = [], string $sort = 'created_at')
    {
        $query = $this->model->query();

        if (isset($filters['topic_id'])) {
            $query->where('topic_id', $filters['topic_id']);
        }

        if (isset($filters['current_state'])) {
            $query->where('current_state', $filters['current_state']);
        }

        if (isset($filters['is_published'])) {
            $query->where('is_published', $filters['is_published']);
        }

        return $query->orderBy($sort, 'desc')->get();
    }


    public function createIdea($data)
    {
        $idea = $this->model->create($data);

        $idea->users()->sync([$data['user_id']]);

        return $idea;
    }

    public function findById($ideaId)
    {
        return $this->model->find($ideaId);
    }

    public function updateIdea(array $data, $ideaId)
    {
        $results = $this->model->where('id', $ideaId)->update($data);
        $result = Idea::findOrFail($results);
        return $result;
    }

    public function deleteIdea($ideaId)
    {
        $this->model->find($ideaId)->users()->detach();
        $this->model->find($ideaId)->logs()->delete();
        $this->model->find($ideaId)->comments()->delete();
        $this->model->find($ideaId)->ratings()->delete();
        return $this->model->where('id', $ideaId)->delete();
    }
}
