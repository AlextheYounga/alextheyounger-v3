<?php

namespace Database\Seeders;

use App\Models\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Repository::truncate();

        $linguistData = json_decode(file_get_contents('storage/app/public/repositories.json'), true);

        foreach ($linguistData as $repository) {
            Repository::create([
                'name' => $repository['name'],
                'path' => $repository['path'],
                'size' => (float) $repository['totalSize'],
                'languages' => $repository['languages'],
            ]);
        }
    }
}
