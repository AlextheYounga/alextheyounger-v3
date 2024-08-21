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
		$privateRepos = [];
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
				$duSize = $lines[2];
				$totalSize = $this->getRepoSize($duSize);

				if (array_key_exists($repoName, $repoList)) {
					$this->warning('Warning: Duplicate repo found: ' . $repoName);
					if ($totalSize <= $repoList[$repoName]['totalSize']) {
						// Keep the bigger one
						continue;
					}
				} 

				$isPrivate = in_array($repoName, $privateReposList);
				$repoData = [
					'name' => $repoName,
					'path' => $isPrivate ? null : $relativeRepoPath,
					'totalSize' => (float) $totalSize,
					'visibility' => $isPrivate ? 'private' : 'public',
					'languages' => $languageData
				];

				$repoList[$repoName] = $repoData;

				// Separate public and private repos
				if ($repoData['visibility'] === 'public') {
					$publicRepos[$repoName] = $repoData;
				} else {
					$privateRepos[$repoName] = $repoData;
				}
			}
		}


		Storage::disk('data')->put('repositories.json', json_encode($publicRepos));
		Storage::disk('public')->put('linguist-private.json', json_encode($privateRepos));

		$this->call(RepositorySeeder::class);
		$this->call(LanguageSeeder::class);
    }

	private function getRepoSize($duSize) {
		{
			// Processes the output of the du command that was run in linguist.sh
			$conversions = [
				'K' => 1000,
				'M' => 1000000,
				'G' => 1000000000,
			];
			
			$outputParts = explode(" ", $duSize);
			$byteMeasure = $outputParts[0][-1];
			$sizeMeasure = str_replace($byteMeasure, '', $outputParts[0]);
			$size = (int) $sizeMeasure * $conversions[$byteMeasure];

			return $size;
		}
	}

}
