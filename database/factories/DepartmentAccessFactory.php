<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\DepartmentAccess;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DepartmentAccess>
 */
class DepartmentAccessFactory extends Factory
{
    protected $model = DepartmentAccess::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::pluck('id')->random(),
            'role_id' => $this->faker->numberBetween(1, 10),
            'department_id' => Department::pluck('id')->random(),
            'status' => $this->faker->boolean(),
            'created_by' => User::pluck('id')->random(),
            'updated_by' => User::pluck('id')->random(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
