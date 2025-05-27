<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface TopicRepositoryInterface
{
    public function getAllFilteredTopics(array $filters, int $perPage = 10);
    public function find(int $id): ?array;
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getTopicDetails(int $id);
}
