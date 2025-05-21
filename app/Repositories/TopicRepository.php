<?php

namespace App\Repositories;

use App\Interfaces\TopicRepositoryInterface;
use App\Models\Topic;

class TopicRepository implements TopicRepositoryInterface
{
    protected $model;

    public function __construct(Topic $model)
    {
        $this->model = $model;
    }

    public function getAllFilteredTopics(array $filters, int $perPage = 10)
    {
        $query = Topic::with(['categories', 'language', 'department'])
            ->when(isset($filters['keyword']), function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['keyword'] . '%');
            })
            ->when(isset($filters['department_id']), function ($q) use ($filters) {
                $departments = explode(',', $filters['department_id']);
                $q->whereIn('department_id', $departments);
            })
            ->when(isset($filters['language_id']), function ($q) use ($filters) {
                $q->where('language_id', $filters['language_id']);
            })
            ->when(isset($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(isset($filters['category_id']), function ($q) use ($filters) {
                $categories = explode(',', $filters['category_id']);
                $q->whereHas('categories', function ($subQ) use ($categories) {
                    $subQ->whereIn('categories.id', $categories);
                });
            });

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        $topics = $query->paginate($perPage);
        return $topics;
        // return new TopicCollection($topics);
    }

    /**
     *
     * @param int $id
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $topic = $this->model->with('categories')->find($id);
        return $topic ? $topic->toArray() : null;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $topic = $this->model->create($data);
        return $topic->toArray();
    }

    /**
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public function getTopicDetails(int $id)
    {
        return $this->model->with(['categories', 'tags'])->findOrFail($id);
    }
}
