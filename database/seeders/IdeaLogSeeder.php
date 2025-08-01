<?php

namespace Database\Seeders;

use App\Models\IdeaLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdeaLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IdeaLog::factory()->count(10)->create();
    }
}