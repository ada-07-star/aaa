<?php

namespace App\Repositories;

use App\Interfaces\ObjectRepositoryInterface;
use App\Models\ObjectModel;
use Illuminate\Http\Request;

/**
 * Class ObjectRepository.
 */
class ObjectRepository implements ObjectRepositoryInterface
{
    protected $model;

    public function __construct(ObjectModel $object)
    {
        $this->model = $object;
    }

    public function getAll(array $filters)
    {
        $query = $this->model->query();

        $query->where('evaluation_id', $filters['evaluation_id']);

        return $query->get();
    }
}
