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
        $directories = $this->buildDirectoryPaths();
        $statistics = [];

        foreach($directories as $dir) {
            $output = [];
            $command = 'github-linguist ' . $dir;

            exec($command, $output);

            if (str_contains($output[0], "invalid revision 'HEAD'")) {
                continue;
            }

            $pathParts = explode('/', $dir);
            $name = end($pathParts);
            $parsedStatistics = $this->parseStatistics($output);
            $standardizedStatistics = $this->languageBytes($parsedStatistics);

            $data = [
                'name' => $name,
                'path' => $dir,
                'host' => 'local',
                'languages' => $standardizedStatistics,
                'visibility' => 'private',
                'properties' => [
                    'languageStatistics' => $parsedStatistics
                ]
            ];

            array_push($statistics, $data);
        }

        if (App::environment('local')) {
            file_put_contents(
                'storage/data/repositories.json',
                json_encode($statistics, JSON_PRETTY_PRINT)
            );
        }

        return $statistics;
    }

    private function buildDirectoryPaths()
    {
        $paths = [];
        $directoriesJson = file_get_contents($this->directories);
        $directories  = json_decode($directoriesJson, true);

        foreach($directories as $dir) {
            $fullPath = env('DEVELOPMENT_FOLDER') . $dir;
            array_push($paths, $fullPath);
        }

        return $paths;
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
