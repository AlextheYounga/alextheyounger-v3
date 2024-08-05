<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\RepositorySeeder;
use Database\Seeders\LanguageSeeder;
use DirectoryIterator;

class ParseLinguistOutput extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:linguist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses the output files created by scripts/linguist.sh and saves them to the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		
		$publicRepos = [];
		$repoList = []; 
		$privateReposList = json_decode(Storage::disk('public')->get('private-repos.json'), true);
		$dir = new DirectoryIterator(storage_path('app/public/linguist'));

		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				// Process data
				$repoName = explode('.', $fileinfo->getFilename())[0];
				$lines = file($fileinfo->getPathname(), FILE_IGNORE_NEW_LINES);
				$repoPath = $lines[0];
				$relativeRepoPath = explode(env('DEVELOPMENT_FOLDER'),$repoPath)[1];
				$languageData = json_decode($lines[1], true);
				$totalSize = $this->getRepoSize($languageData);

				if (array_key_exists($repoName, $repoList)) {
					$this->warning('Warning: Duplicate repo found: ' . $repoName);
					if ($totalSize <= $repoList[$repoName]['totalSize']) {
						// Keep the bigger one
						continue;
					}
				} 

				$repoData = [
					'name' => $repoName,
					'path' => $relativeRepoPath,
					'totalSize' => $totalSize,
					'visibility' => in_array($repoName, $privateReposList) ? 'private' : 'public',
					'languages' => $languageData
				];

				$repoList[$repoName] = $repoData;

				if ($repoData['visibility'] === 'public') {
					$publicRepos[$repoName] = $repoData;
				}
			}
		}

		Storage::disk('public')->put('linguist.json', json_encode($repoList));
		Storage::disk('data')->put('repositories.json', json_encode($publicRepos));

		$this->call(RepositorySeeder::class);
		$this->call(LanguageSeeder::class);
    }

	private function getRepoSize($languageData) {
		{
			$totalSize = 0;
	
			foreach($languageData as $language => $data) {
				$totalSize += $data['size'];
			}

			return $totalSize;
		}
	}
}
