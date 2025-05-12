<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface DepartmentRepositoryInterface
{
    public function getAll(Request $request);
    public function create(array $data, int $creatorId);
    public function softDelete($id);
    public function find($id);
    public function getDepartmentById($id);

}
