<?php

namespace App\Interfaces;

interface TopicCategoryRepositoryInterface
{
    public function getTopicCategories(array $filters = []);
    public function findById($id);
    public function createTopicCategories(array $data);
    public function deleteTopicCategories($id);
}
