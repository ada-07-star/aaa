<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\Department;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'department_id' => Department::all()->random()->id,
            'language_id' => Language::all()->random()->id,
            'age_range' => $this->faker->randomElement(['CHILD', 'TEEN', 'ADULT']), 
            'gender' => $this->faker->randomElement([1, 2]),
            'thumb_image' => $this->faker->imageUrl(640, 480, 'thumb'),
            'cover_image' => $this->faker->imageUrl(1280, 720, 'cover'),
            'submit_date_from' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'submit_date_to' => $this->faker->dateTimeBetween('now', '+1 month'),
            'consideration_date_from' => $this->faker->dateTimeBetween('now', '+2 months'),
            'consideration_date_to' => $this->faker->dateTimeBetween('+2 months', '+3 months'),
            'plan_date_from' => $this->faker->dateTimeBetween('+3 months', '+4 months'),
            'plan_date_to' => $this->faker->dateTimeBetween('+4 months', '+5 months'),
            'current_state' => $this->faker->randomElement(['pending', 'approved', 'rejected']), 
            'judge_number' => $this->faker->numberBetween(1, 10), 
            'minimum_score' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->boolean,  
            'is_archive' => $this->faker->boolean, 
            'created_by' => $this->faker->numberBetween(1, 100),
            'updated_by' => $this->faker->numberBetween(1, 100)
        ];  
    }
}
