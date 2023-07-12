<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class ScanMachineRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scan-repos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans machine for repos based on DEVELOPMENT_FOLDER env variable';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if (App::environment('local')) {
            $output = [];
            $directories = [];
            $devDirectory = env('DEVELOPMENT_FOLDER');

            $blacklist = json_decode(file_get_contents('storage/app/linguist-blacklist.json'), true);

            // Get git enabled directories.
            $command = 'find ' . $devDirectory . ' -maxdepth 6 -name ".git" -print';
            exec($command, $output);

            foreach($output as $dir) {
                if (str_contains($dir, 'Cloned/') || str_contains($dir, 'Forks/')) {
                    continue;
                }

                $correctDirectory = str_replace('.git', '', $dir);
                $trimmedDirectory = rtrim($correctDirectory, '/');

                $pathParts = explode('/', $trimmedDirectory);
                $name = end($pathParts);
    
                if (in_array($name, $blacklist)) {
                    $this->warn('Found ' . $trimmedDirectory . " in blacklist. Skipping...");
                    continue;
                }

                array_push($directories, $trimmedDirectory);

                $this->info('Found ' . $trimmedDirectory . '...');
            }

            file_put_contents(
                'storage/app/directories.json',
                json_encode($directories, JSON_PRETTY_PRINT)
            );

        }
    }
}
