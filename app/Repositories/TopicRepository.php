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
    }

    /**
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id)
    {
        $topic = $this->model->with('categories')->find($id);
        return $topic;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {
        $topic = $this->model->create([
            'title' => $data['title'],
            'department_id' => $data['department_id'],
            'language_id' => $data['language_id'],
            'age_range' => $data['age_range'],
            'gender' => $data['gender'] ?? null,
            'thumb_image' => $data['thumb_image'] ?? null,
            'cover_image' => $data['cover_image'] ?? null,
            'submit_date_from' => $data['submit_date_from'],
            'submit_date_to' => $data['submit_date_to'] ?? null,
            'consideration_date_from' => $data['consideration_date_from'] ?? null,
            'consideration_date_to' => $data['consideration_date_to'] ?? null,
            'plan_date_from' => $data['plan_date_from'] ?? null,
            'plan_date_to' => $data['plan_date_to'] ?? null,
            'current_state' => $data['current_state'],
            'judge_number' => $data['judge_number'],
            'minimum_score' => $data['minimum_score'],
            'evaluation_id' => $data['evaluation_id'] ?? null,
            'status' => $data['status'],
            'is_archive' => $data['is_archive'],
            'created_by' => $data['created_by'],
            'updated_by' => $data['updated_by']
        ]);
        return $topic;
    }

    /**
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $validatedData): bool
    {
        return $this->model->find($id)->update($validatedData);
    }
}
