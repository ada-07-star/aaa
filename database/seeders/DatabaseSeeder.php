<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            DepartmentSeeder::class,
            EvaluationSeeder::class,
            DepartmentAccessSeeder::class,
            LanguageSeeder::class,
            TopicSeeder::class,
            IdeaSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            TopicTagSeeder::class,
            IdeaLogSeeder::class,
            IdeaUserSeeder::class,
            IdeaCommentSeeder::class,
            IdeaRatingSeeder::class,
            EvaluationObjectSeeder::class,
        ]);
    }
}
