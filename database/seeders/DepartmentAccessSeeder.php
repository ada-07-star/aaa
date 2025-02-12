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
        // \App\Models\User::factory(1)->has(\App\Models\DepartmentAccess::factory(10), 'subjobs')->create()
        DepartmentAccess::factory()->count(10)->create();
    }
}
