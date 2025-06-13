<?php

namespace Database\Seeders;

use App\Models\ObjectModel;
use Illuminate\Database\Seeder;

class ObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObjectModel::factory()->count(10)->create();
    }
}
