<?php

namespace App\Interfaces;

interface TagRepositoryInterface
{   
    public function getAllTags(array $filters, string $sort);
    public function createTag($data);
    public function updateTag($id, $data);
    public function deleteTag($id);
    public function getTagById($id);
    
}
