<?php

namespace Database\Seeders;

use App\Models\IdeaRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdeaRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IdeaRating::factory()->count(10)->create();
    }
}
