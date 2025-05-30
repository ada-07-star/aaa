<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * @OA\Schema(
 *     schema="TopicTagIndexResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="فناوری اطلاعات"),
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date", example="1402/01/01")
 * )
 *
 * @OA\Schema(
 *     schema="TopicTagShowResource",
 *     type="object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/TopicTagIndexResource"),
 *         @OA\Schema(
 *             @OA\Property(property="id", type="integer", example="1"),
 *             @OA\Property(property="title", type="string", format="date", example="1402/01/01"),
 *             @OA\Property(property="description", type="string", example="توضیحات تگ"),
 *             @OA\Property(property="created_at", type="date", example=101)
 *         )
 *     }
 * )
 */
class TopicTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
