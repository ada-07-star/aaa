<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface EvaluationObjectRepositoryInterface
{
    public function getEvaluationObjects(Request $request);
    public function createEvaluationObject(array $data);
    public function updateEvaluationObjectOrder($id, $order_of);
    public function deleteEvaluationObject($id);
}
