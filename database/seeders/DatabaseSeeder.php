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
        $this->call(CategorySeeder::class);
        $this->call(BookSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(RepositorySeeder::class);
        $this->call(CodingLanguageSeeder::class);
    }
}
