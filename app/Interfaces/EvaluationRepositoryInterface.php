<?php

namespace App\Interfaces;

interface EvaluationRepositoryInterface
{
    public function getAll(array $filters);
    public function create(array $data, int $creatorId);
    public function update($id, array $data);
    public function delete($id);
    public function find($id);
}
