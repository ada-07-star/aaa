<?php

namespace App\Repositories;

use App\Interfaces\EvaluationRepositoryInterface;
use App\Models\Evaluation;

/**
 * Class EvaluationRepository.
 */
class EvaluationRepository implements EvaluationRepositoryInterface
{
    protected $model;

    public function __construct(Evaluation $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters)
    {
        $query = $this->model->query();

        if (isset($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    public function create(array $data, int $creatorId)
    {
        return $this->model->create([
                'title' => $data['title'],
                'department_id' => $data['department_id'],
                'description' => $data['description'],
                'status' => $data['status'],
                'created_by' => $creatorId,
                'updated_by' => $creatorId,
            ]);
    }

    public function update($id, array $data)
    {
        $evaluation = $this->model->findOrFail($id);
        $evaluation->update($data);
        return $evaluation;
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }
}
