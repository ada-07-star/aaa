<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="ObjectIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="evaluation_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="گویه های یک ارزشیابی"),
 *     @OA\Property(property="description", type="string", example="توضیحات گویه های یک ارزشیابی"),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="ObjectShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ObjectShowResource"),
 *         @OA\Schema(
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="evaluation_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="گویه های یک ارزشیابی"),
 *     @OA\Property(property="description", type="string", example="توضیحات گویه های یک ارزشیابی"),
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
class ObjectResource extends JsonResource
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
            'evaluation_id' => $this->evaluation_id,
            'object_id' => $this->object_id,
            'order_of' => $this->order_of,
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'evaluation_id' => $this->evaluation_id,
            'object_id' => $this->object_id,
            'order_of' => $this->order_of,
        ];
    }
}