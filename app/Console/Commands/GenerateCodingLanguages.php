<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CodingLanguage;
use App\Models\Repository;

class GenerateCodingLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'languages:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate coding languages from repos.';
    protected $settings = [
        'additions' => [
            'PHP' => 3000000,
            'Ruby' => 4204694,
            'JavaScript' => 611638,
        ],
        // Subtract percent from total
        'subtractions' => [
            'Ruby' => 0.4, // Account for generated code I didn't write
            'PHP' => 0.4, // Account for generated code I didn't write
            'JavaScript' => 0.5, // Every framework contains generated JavaScript code
        ],
        'ignore' => [
            'Markdown',
            'ASP.NET',
            'MDX',
            'Dockerfile',
            'Elixir',
            'HTML',
            'CSS',
            'SCSS',
            'Blade',
            'ASL',
            'CoffeeScript',
            'Starlark',
            'EJS',
            'Nix',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CodingLanguage::truncate();
        $repos = Repository::all();
        $ignoreLanguages = $this->settings['ignore'] ?? [];

        foreach ($repos as $repo) {
            $this->info('Parsing languages from repo: ' . $repo->name);
            $languages = $repo->languages ?? [];

            foreach ($languages as $lang => $data) {
                $language = new CodingLanguage();
                $language->language = $lang;
                $language->value = $data['size'];
                $language->display_value = $data['size']; // Not implemented
                $language->active = !in_array($lang, $ignoreLanguages);
                $language->color = $language->getLanguageColor();
                $language->properties = [
                    'slug' => $language->slugifyLanguage(),
                    'parameterized' => parameterize($lang),
                    'rawData' => [$lang => $data],
                ];
                $language->getProjectCount();
                $language->incrementOrCreate();
            }
        }

        $this->runWeightAdjustments();
        $this->calculateTableWidths();

        $this->info('Successfully compiled coding languages.');
    }

    private function runWeightAdjustments()
    {
        $languages = CodingLanguage::all();

        foreach ($languages as $lang) {
            if (array_key_exists($lang->language, $this->settings['additions'])) {
                $lang->display_value += $this->settings['additions'][$lang->language];
            }

            if (array_key_exists($lang->language, $this->settings['subtractions'])) {
                $percentValue =
                    $lang->display_value * $this->settings['subtractions'][$lang->language];
                $lang->display_value -= $percentValue;
            }

            $lang->save();
        }
    }

    private function calculateTableWidths()
    {
        $languages = CodingLanguage::active()->get();
        $total = CodingLanguage::active()->sum('display_value');

        foreach ($languages as $language) {
            $width = round(($language->display_value / $total) * 100, 2);
            $language->width = $width;

            $language->save();
        }
    }
}
