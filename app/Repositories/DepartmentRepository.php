<?php

namespace App\Repositories;

use App\Interfaces\DepartmentRepositoryInterface;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
            'data' => $departments->items(),
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
        $department = $this->find($id);
        if (!$department) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('بخش مورد نظر یافت نشد');
        }
        return $department;
    }

    public function updateDepartment($id, array $data, $updatedBy)
    {
        $department = $this->find($id);
        if (!$department) {
            return false;
        }

        $validator = Validator::make($data, [
            'title' => 'sometimes|string|max:500',
            'descriptions' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $updateData = [
            'title' => $data['title'] ?? $department->title,
            'descriptions' => $data['descriptions'] ?? $department->descriptions,
            'status' => $data['status'] ?? $department->status,
            'updated_by' => $updatedBy
        ];

        $department->update($updateData);

        return $department;
    }

    public function softDelete($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return false;
        }
        $department->delete();
        return true;
    }
}
