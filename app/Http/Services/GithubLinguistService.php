<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\App;

class GithubLinguistService
{
    protected $directories;

    public function __construct($directoriesList = null)
    {
        $this->directories = $directoriesList ?? 'storage/data/directories.json';
    }

    public function runLinguist()
    {
        $directories = $this->getDirectoryPaths();
        $blacklist = json_decode(file_get_contents('storage/app/linguist-blacklist.json'), true);
        $repositories = [];
        $statistics = [];

        foreach($directories as $dir) {
            $output = [];
            $command = 'github-linguist ' . $dir;

            $pathParts = explode('/', $dir);
            $name = end($pathParts);

            if (\in_array($name, $blacklist)) {
                print($dir . " in blacklist. Skipping...\n");
                continue;
            }

            exec($command, $output);

            if (empty($output) || str_contains($output[0], "invalid revision 'HEAD'")) {
                print('Failed to parse ' . $dir . "...\n");
                continue;
            }
            
            $parsedStatistics = $this->parseStatistics($output);
            $standardizedStatistics = $this->languageBytes($parsedStatistics);

            $repository = [
                'name' => $name,
                'host' => 'local',
                'languages' => $standardizedStatistics,
                'properties' => [
                    'languageStatistics' => $parsedStatistics
                ]
            ];

            $data = [
                'name' => $name,
                'path' => $dir,
                'languages' => $parsedStatistics,
            ];

            array_push($repositories, $repository);
            array_push($statistics, $data);
        }

        if (App::environment('local')) {
            file_put_contents(
                'storage/data/repositories.json',
                json_encode($repositories, JSON_PRETTY_PRINT)
            );
            file_put_contents(
                'storage/app/linguist.json',
                json_encode($statistics, JSON_PRETTY_PRINT)
            );
        }

        return $statistics;
    }

    private function getDirectoryPaths()
    {
        $output = [];
        $fullPaths = [];
        $devDirectory = env('DEVELOPMENT_FOLDER');

        // Get git enabled directories.
        $command = 'find ' . $devDirectory . ' -maxdepth 6 -name ".git" -print';
        exec($command, $output);

        foreach($output as $dir) {
            if (str_contains($dir, 'Cloned/') || str_contains($dir, 'Forks/')) {
                continue;
            }

            $correctDirectory = str_replace('.git', '', $dir);
            $trimmedDirectory = rtrim($correctDirectory, '/');
            array_push($fullPaths, $trimmedDirectory);
        }

        return $fullPaths;
    }

    private function parseStatistics($output)
    {
        $parsedStatistics = [];

        foreach($output as $item) {
            $unfilteredParts = explode(' ', $item);
            $parts = array_values(array_filter($unfilteredParts, function ($value) {
                return $value !== '';
            }));

            $percentage = (float) $parts[0];
            $bytes = (int) $parts[1];
            $language = end($parts);

            $parsedStatistics[$language] = [
                'percentage' => $percentage,
                'bytes' => $bytes
            ];
        }

        return $parsedStatistics;
    }

    private function languageBytes($parsedStatistics)
    {
        $standardizedStatistics = [];

        foreach($parsedStatistics as $language => $statistics) {
            $standardizedStatistics[$language] = (int) $statistics['bytes'];
        }

        return $standardizedStatistics;
    }
}
