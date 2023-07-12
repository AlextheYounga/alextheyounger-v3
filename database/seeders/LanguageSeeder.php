<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Repository;


class LanguageSeeder extends Seeder
{
    protected $settings;
    protected $colors;

    public function run(): void
    {
        Language::truncate();

        $repos = Repository::all();

        $ignoreLanguages = Language::settings('ignore');

        foreach($repos as $repo) {
            $languages = $repo->languages ?? [];

            foreach($languages as $lang => $value) {
                $language = new Language([
                    'language' => $lang,
                    'value' => $value,
                    'display_value' => $value,
                    'color' => Language::getLanguageColor($lang),
                    'active' => !in_array($lang, $ignoreLanguages),
                    'properties' => [
                        'slug' => Language::slugifyLanguage($lang),
                        'parameterized' => parameterize($lang),
                    ]
                ]);


                $language->runRepoSpecificValueAdjustments($repo->name);
                $language->getProjectCount();
                $language->incrementOrCreate();
            }
        }

        Language::runWeightAdjustments();
        Language::calculateTableWidths();
    }
}
