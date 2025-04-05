<?php

namespace Database\Seeders;

use App\Models\DepartmentAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DepartmentAccess::factory()->count(10)->create();
    }
}
