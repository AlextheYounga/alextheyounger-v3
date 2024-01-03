<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Repository;
use Illuminate\Database\Seeder;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Repository::truncate();

        $repositoriesJson = file_get_contents('storage/app/data/repositories.json');
        $repositories  = json_decode($repositoriesJson, true);

        foreach($repositories as $repository) {
            Repository::create($repository);
        }
    }
}
