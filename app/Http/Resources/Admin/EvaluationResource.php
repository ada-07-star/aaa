<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="EvaluationIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="عنوان ارزشیابی"),
 *     @OA\Property(property="department_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="EvaluationShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/EvaluationShowResource"),
 *         @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="عنوان ارزشیابی"),
 *     @OA\Property(property="evaluation_rating_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="active"),
 *   @OA\Property(property="created_at", type="string", format="date", example="1402/01/01"),
 *    @OA\Property(property="updated_at", type="string", format="date", example="1402/01/01"),
 *     @OA\Property(property="created_by", type="integer", example=1),
 *     @OA\Property(property="updated_by", type="integer", example=1)
 *         )
 *     }
 * )
 *
 */
class EvaluationResource extends JsonResource
{
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }

    public function toArray(Request $request): array
    {
        $mode = $this->additional['mode'] ?? 'index';

        return match ($mode) {
            'show' => $this->forShow(),
            default => $this->forIndex(),
        };
    }


    protected function forIndex()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'department_id' => $this->department_id,
            // 'evaluation_rating_id' => $this->evaluation_rating_id,
            'status' => $this->status,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'department_id' => $this->department_id,
            // 'evaluation_rating_id' => $this->evaluation_rating_id,
            'status' => $this->status,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ];
    }
}
