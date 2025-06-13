<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="EvaluationObjectResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="evaluation_id", type="integer", example=1),
 *     @OA\Property(property="object_id", type="integer", example=1),
 *     @OA\Property(property="order_of", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 */
class EvaluationObjectResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'evaluation_id' => $this->evaluation_id,
            'object_id' => $this->object_id,
            'order_of' => $this->order_of,
        ];
    }
}
