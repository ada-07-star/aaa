<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = Topic::factory()->count(10)->create();

        $categories = Category::factory()->count(5)->create();

        $topics->each(function ($topic) use ($categories) {
            $topic->categories()->attach(
                $categories->random(rand(2, 4))->pluck('id')->toArray()
            );
        });
    }
}
