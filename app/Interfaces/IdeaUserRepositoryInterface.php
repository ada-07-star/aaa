<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
interface IdeaUserRepositoryInterface
{
    public function getIdeaUsers(Request $request);
    public function findById($id);
    public function createIdeaUser(array $data);
    public function deleteIdeaUser($id);
}
