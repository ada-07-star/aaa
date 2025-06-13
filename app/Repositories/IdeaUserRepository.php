<?php

namespace App\Repositories;

use App\Interfaces\IdeaUserRepositoryInterface;
use App\Models\IdeaUser;
use Illuminate\Http\Request;

/**
 * Class IdeaUserRepository.
 */
class IdeaUserRepository implements IdeaUserRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    protected $model;
    public function __construct(IdeaUser $model)
    {
        $this->model = $model;
    }
    public function getIdeaUsers(Request $request)
    {
        return $this->model
        ->where('idea_id', $request->idea_id)
        ->with(['user', 'idea'])
        ->paginate(15);
    }
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function createIdeaUser(array $data)
    {
        return $this->model->create($data);
    }

    public function deleteIdeaUser($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
