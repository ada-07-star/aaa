<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;

/**
 * Class TagRepository.
 */
class TagRepository implements TagRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    protected $model;
    public function __construct(Tag $model)
    {
        $this->model = $model;
    }
    public function getAllTags(array $filters, string $sort)
    {
        $query = $this->model->query();
        if (isset($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }
        $sortField = $sort ?? 'created_at';
        $query->orderBy($sortField, 'desc');

        return $query->get();
    }

    public function createTag($data)
    {
        return $this->model->create($data);
    }

    public function getTagById($id)
    {
        return $this->model->find($id);
    }

    public function updateTag($id, $data)
    {
        return $this->model->find($id)->update($data);
    }

    public function deleteTag($id)
    {
        $this->model->find($id)->topics()->detach();
        return $this->model->find($id)->delete();
    }
}
