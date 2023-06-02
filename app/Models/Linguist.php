<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Linguist extends Model
{
    use HasFactory;

    public static function runLinguist()
    {
        $directories = Linguist::buildDirectoryPaths();
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
            $parsedStatistics = Linguist::parseStatistics($output);
            $standardizedStatistics = Linguist::languageBytes($parsedStatistics);

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

    private static function languageBytes($parsedStatistics)
    {
        $standardizedStatistics = [];

        foreach($parsedStatistics as $language => $statistics) {
            $standardizedStatistics[$language] = (int) $statistics['bytes'];
        }

        return $standardizedStatistics;
    }

    private static function parseStatistics($output)
    {
        $parsedStatistics = [];

        foreach($output as $item) {
            $unfilteredParts = explode(' ', $item);
            $parts = array_values(array_filter($unfilteredParts, function($value) {
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

    private static function buildDirectoryPaths()
    {
        $paths = [];
        $directoriesJson = file_get_contents('storage/data/directories.json');
        $directories  = json_decode($directoriesJson, true);

        foreach($directories as $dir) {
            $fullPath = env('DEVELOPMENT_FOLDER') . $dir;
            array_push($paths, $fullPath);
        }

        return $paths;
    }
}
