<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function getAllFilteredCategories(array $filters);
    public function findById(int $id);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function createCategory(array $data);
} 