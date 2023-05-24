<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GithubController;

class FetchLanguagesFromGithub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:languages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch github languages from Github API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new GithubController();
        $controller->fetchLanguagesFromGithub();
    }

    
}
