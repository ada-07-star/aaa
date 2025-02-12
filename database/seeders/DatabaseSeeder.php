<?php

namespace Database\Seeders;

use App\Models\DepartmentAccess;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            DepartmentSeeder::class,
            DepartmentAccessSeeder::class,
            LanguageSeeder::class,
            TopicSeeder::class,
            IdeaSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            TopicTagSeeder::class,
            IdeaLogSeeder::class,
            IdeaUserSeeder::class,

        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
