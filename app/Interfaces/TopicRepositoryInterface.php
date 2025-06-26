<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface TopicRepositoryInterface
{
    public function getAllTopicsUsers(array $filters, int $perPage = 10);
    public function getAllFilteredTopics(array $filters, int $perPage = 10);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $validatedData): bool;
}
