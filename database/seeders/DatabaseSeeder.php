<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Category;
use \App\Models\User;
use \App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Testuser',
            'username' => 'test-user',
            'email' => 'test@example.com'
        ]);

        $categories = [
            'Lifestyle',
            'Reisen',
            'Essen & Getränke',
            'Technologie',
            'Gesundheit & Fitness',
            'Unterhaltung',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        //Post::factory(100)->create();
    }
}
