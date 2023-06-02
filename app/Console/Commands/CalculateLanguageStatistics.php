<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Language;

class CalculateLanguageStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:languages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the calculateLanguageStatistics function from the Language model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Language::calculateLanguageStatistics();
    }
}
