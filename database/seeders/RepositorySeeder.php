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

		$linguistData = json_decode(Storage::disk('data')->get('repositories.json'), true);

		if (file_exists('storage/app/public/linguist-private.json')) {
			$privateLinguistData = json_decode(Storage::disk('public')->get('linguist-private.json'), true);
			$linguistData = array_merge($linguistData, ($privateLinguistData ?? []));
		}

        foreach($linguistData as $repository) {
            Repository::create([
                'name' => $repository['name'],
				'path' => $repository['visibility'] === 'public' ? $repository['path'] : null,
                'size' => (float) $repository['totalSize'],
				'visibility' => $repository['visibility'],
                'languages' => $repository['languages'],
            ]);
        }
    }
}
