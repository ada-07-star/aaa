<?php

namespace Database\Seeders;

use App\Models\IdeaUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdeaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IdeaUser::factory()->count(10)->create();
    }
}
