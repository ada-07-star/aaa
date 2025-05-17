<?php

namespace App\Repositories;

use App\Http\Resources\AdminDepartmentResource;
use App\Interfaces\DepartmentRepositoryInterface;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class DepartmentRepository.
 */
class DepartmentRepository implements DepartmentRepositoryInterface
{
    protected $model;

    public function __construct(Department $department)
    {
        $this->model = $department;
    }

    public function getAll(Request $request)
    {
        $query = $this->model->query();

        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('descriptions', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $departments = $query->orderBy('created_at', 'desc')->paginate($perPage);
        return [
            'success' => true,
            'message' => 'لیست دپارتمان‌ها با موفقیت دریافت شد',
            'data' => AdminDepartmentResource::collection($departments),
            'meta' => [
                'current_page' => $departments->currentPage(),
                'per_page' => $departments->perPage(),
                'total' => $departments->total(),
            ]
        ];
    }

    public function create(array $data, int $creatorId): Department
    {
        return Department::create([
            'title' => $data['title'],
            'descriptions' => $data['descriptions'] ?? null,
            'status' => $data['status'],
            'created_by' => $creatorId,
            'updated_by' => $creatorId,
        ]);
    }

    public function find($id)
    {
        return Department::find($id);
    }

    public function getDepartmentById($id)
    {
        $department = $this->model->with(['creator', 'updater'])->findOrFail($id);

        return  $department;
    }

    public function updateDepartment($id, array $data, $updatedBy)
    {
        $department = $this->model->findOrFail($id);

        $validated = Validator::validate($data, [
            'title' => 'sometimes|string|max:500',
            'descriptions' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ]);

        $updateData = array_merge(
            array_filter($validated),
            ['updated_by' => $updatedBy]
        );

        $department->update($updateData);

        return $department->fresh();
    }

    public function softDelete($id)
    {
        $department = $this->model->findOrFail($id);
        
        $department->delete();
        return true;
    }
}
