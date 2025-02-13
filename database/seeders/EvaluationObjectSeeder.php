<?php

namespace Database\Seeders;

use App\Models\EvaluationObject;
use Illuminate\Database\Seeder;

class EvaluationObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EvaluationObject::factory()->count(10)->create();
    }
}
