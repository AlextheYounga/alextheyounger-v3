<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Services\GithubLinguistService;
use Illuminate\Support\Facades\App;
use Database\Seeders\RepositorySeeder;
use Database\Seeders\LanguageSeeder;
use App\Models\Language;

class RunGithubLinguist extends Command
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
    protected $description = 'Runs the runLinguist function from the Linguist model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (App::environment('local')) {
            $linguist = new GithubLinguistService();
            $linguist->runLinguist();

            $this->call(RepositorySeeder::class);
            $this->call(LanguageSeeder::class);

        } else {
            $this->error('This command only runs in local environments');
        }
        
    }
}
