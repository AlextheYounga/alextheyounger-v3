<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class GithubLinguistService
{
    protected $directories;

    public function __construct($directoriesList = null)
    {
        // Requires directories to be scanned first. Run app:scan-repos first.
        $this->directories = $directoriesList ?? json_decode(Storage::disk('public')->get('directories.json'));
    }

    public function runLinguist()
    {
        $repositories = [];
        $statistics = [];

        foreach($this->directories as $dir) {

            print('Scanning ' . $dir . "...\n");

            $data = $this->getLinguistStatistics($dir);
            $size = $this->getDirectorySize($dir);

            $pathParts = explode('/', $dir);
            $name = end($pathParts);

            if (empty($data) || str_contains($data[0], "invalid revision 'HEAD'")) {
                print('Failed to parse ' . $dir . "...\n");
                continue;
            }

            $parsedStatistics = $this->parseStatistics($data);
            $standardizedStatistics = $this->languageBytes($parsedStatistics);

            $repository = [
                'name' => $name,
                'host' => 'local',
                'size' => $size,
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
            Storage::disk('public')->put('linguist.json', json_encode($statistics, JSON_PRETTY_PRINT));
            file_put_contents(
                'storage/app/data/repositories.json',
                json_encode($repositories, JSON_PRETTY_PRINT)
            );
        }

        return $statistics;
    }

    private function getLinguistStatistics($dir)
    {
        $data = [];

        // Run Linguist command
        $command = 'github-linguist ' . $dir;
        exec($command, $data);

        return $data;
    }

    private function getDirectorySize($dir)
    {
        $conversions = [
            'K' => 1000,
            'M' => 1000000,
            'G' => 1000000000,
        ];

        $size = [];

        // Run size command
        $command = 'du -sh ' . $dir;
        exec($command, $size);

        if (!empty($size)) {
            $size = explode("\t", $size[0])[0];
            $byteMeasure = $size[-1];
            
            $size = (int) $size * $conversions[$byteMeasure];

            return $size;
        }
        
        return null;
    }

    private function parseStatistics($data)
    {
        $parsedStatistics = [];

        foreach($data as $item) {
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
