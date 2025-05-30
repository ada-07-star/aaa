<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * @OA\Schema(
 *     schema="TagIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="فناوری اطلاعات"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="TagShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TagIndexResource"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", example="1"),
 *             @OA\Property(property="title", type="string", format="date", example="1402/01/01"),
 *             @OA\Property(property="description", type="string", example="توضیحات تگ"),
 *             @OA\Property(property="created_at", type="date", example=101)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="TagStoreResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=5)
 * )
 */
class TagResource extends JsonResource
{
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mode = $this->additional['mode'] ?? 'index';

        return match ($mode) {
            'store' => $this->forStore(),
            'show' => $this->forShow(),
            default => $this->forIndex(),
        };
    }

    protected function forIndex()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }

    protected function forStore()
    {
        return [
            'id' => $this->id,
        ];
    }

    protected function forShow()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->formatDate($this->created_at)
        ];
    }
}
