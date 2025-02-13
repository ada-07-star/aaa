<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IdeaComment;

class IdeaCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IdeaComment::factory()->count(10)->create();
    }
}
