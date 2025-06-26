<?php

namespace App\Repositories;

use App\Interfaces\TopicCategoryRepositoryInterface;
use App\Models\TopicCategory;

/**
 * Class TopicCategoryRepository.
 */
class TopicCategoryRepository implements TopicCategoryRepositoryInterface
{
    protected $model;

    public function __construct(TopicCategory $model)
    {
        $this->model = $model;
    }

    public function getTopicCategories(array $filters = [])
    {
        return $this->model
            ->when(isset($filters['topic_id']), fn($q) => $q->where('topic_id', $filters['topic_id']))
            ->when(isset($filters['category_id']), fn($q) => $q->where('category_id', $filters['category_id']))
            ->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function createTopicCategories(array $data)
    {
        return $this->model->create($data);
    }

    public function deleteTopicCategories($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
