<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // یا هر مقدار دلخواه دیگر  
            'department_id' => Department::pluck('id')->random(), // انتخاب یک department_id تصادفی  
            'description' => $this->faker->paragraph(), // توضیحات تصادفی  
            'status' => $this->faker->boolean(), // وضعیت تصادفی  
            'created_by' => $this->faker->randomNumber(), // شناسه کاربر ایجادکننده  
            'updated_by' => $this->faker->randomNumber(), // شناسه کاربر به‌روزرسانی‌کننده  
            'created_at' => now(),  
            'updated_at' => now(),
        ];
    }
}
