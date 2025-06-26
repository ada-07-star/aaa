<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getAllFilteredCategories(array $filters)
    {
        $query = $this->model->query();

        if (isset($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sortField = $filters['sort'] ?? 'created_at';
        $query->orderBy($sortField, 'desc');

        return $query->get();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function createCategory(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }
}
