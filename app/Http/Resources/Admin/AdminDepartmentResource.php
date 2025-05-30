<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class AdminDepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'descriptions' => $this->descriptions,
            'status' => $this->status,
            'created_by' => $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email
            ] : null,
            'update_by' => $this->updater ? [
                'id' => $this->updater->id,
                'name' => $this->updater->name,
                'email' => $this->updater->email
            ] : null,
            'created_at' => Jalalian::fromDateTime( $this->created_at)->format('Y/m/d'),
            'updated_at' => Jalalian::fromDateTime( $this->updated_at)->format('Y/m/d'),
        ];
    }
}
