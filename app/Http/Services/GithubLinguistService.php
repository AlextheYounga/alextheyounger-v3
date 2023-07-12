<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\App;

class GithubLinguistService
{
    protected $directories;

    public function __construct($directoriesList = null)
    {
        // Requires directories to be scanned first. Run app:scan-repos first.
        $this->directories = $directoriesList ?? 'storage/app/directories.json';
    }

    public function runLinguist()
    {
        $repositories = [];
        $statistics = [];

        foreach($this->directories as $dir) {
            $output = [];
            $command = 'github-linguist ' . $dir;
            exec($command, $output);

            $pathParts = explode('/', $dir);
            $name = end($pathParts);

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
