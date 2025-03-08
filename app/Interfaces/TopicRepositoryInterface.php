<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface TopicRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?array;
    public function create(array $data): array;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getTopicsList(): Collection;
    public function getTopicDetails(int $id): array;
}
