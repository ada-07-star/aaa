<?php

namespace App\Repositories;

use App\Models\EvaluationObject;
use Illuminate\Http\Request;
use App\Interfaces\EvaluationObjectRepositoryInterface;

/**
 * Class EvaluationObjectRepository.
 */
class EvaluationObjectRepository implements EvaluationObjectRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    protected $model;
    public function __construct(EvaluationObject $model)
    {
        $this->model = $model;
    }
    public function getEvaluationObjects(Request $request)
    {
        return $this->model
        ->where('evaluation_id', $request->evaluation_id)
        ->with(['object', 'evaluation'])
        ->paginate(15);
    }
    public function createEvaluationObject(array $data)
    {
        return $this->model->create($data);
    }
    public function updateEvaluationObjectOrder($id, $order_of)
    {
        $evaluationObject = $this->model->findOrFail($id);
      
        $evaluationObject->order_of = $order_of;
        $evaluationObject->save();
        return true;
    }
    public function deleteEvaluationObject($id)
    {
        $evaluationObject = $this->model->findOrFail($id);

        $evaluationObject->delete();
        return true;
    }
}
