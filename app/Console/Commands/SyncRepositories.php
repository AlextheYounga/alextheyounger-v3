<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GithubController;
// use App\Http\Controllers\GitlabController;
use Database\Seeders\LanguageSeeder;

class SyncRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:repos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch repositories and languages from Github and Gitlab API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $github = new GithubController();
        $github->runSync();

        // $gitlab = new GitlabController();
        // $gitlab->runSync();

        $this->call(LanguageSeeder::class);
    }

    
}
