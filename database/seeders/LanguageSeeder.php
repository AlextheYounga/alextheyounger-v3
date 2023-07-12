<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Repository;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;


class LanguageSeeder extends Seeder
{
    protected $settings;
    protected $colors;

    public function run(): void
    {
        Language::truncate();

        $repos = Repository::all();

        $this->settings = Yaml::parseFile('storage/app/language-settings.yml');
        $this->colors = json_decode(file_get_contents('resources/data/language-colors.json'), true);

        $ignoreLanguages = $this->settings['ignore'];

        foreach($repos as $repo) {
            $languages = $repo->languages ?? [];

            foreach($languages as $lang => $value) {
                $language = [
                    'language' => $lang,
                    'value' => $value,
                    'color' => $this->colors[$lang],
                    'active' => !in_array($lang, $ignoreLanguages),
                    'properties' => [
                        'slug' => $this->slugifyLanguage($lang),
                        'parameterized' => parameterize($lang),
                    ]
                ];

                $language['display_value'] = $this->repoSpecificValueAdjustments($repo->name, $language);
                Language::incrementOrCreate($language);
            }
        }

        $this->runFinalWeightAdjustments();
        $this->calculateTableWidths();
    }

    private function repoSpecificValueAdjustments($repoName, $language)
    {
        $repoSettings = $this->settings['repo-specific'];

        if (array_key_exists($repoName, $repoSettings)) {
            $suppressLanguage = $repoSettings[$repoName]["language"];

            if ($suppressLanguage === $language['language']) {
                $suppressBy = $repoSettings[$repoName]["suppressBy"];
                return (int) ($language['value'] * $suppressBy);
            }
        }
        return $language['value'];
    }

    private function runFinalWeightAdjustments()
    {
        $languages = Language::all();

        $additions = $this->settings['additions'];
        $subtractions = $this->settings['subtractions'];

        foreach($languages as $lang) {
            if (array_key_exists($lang->language, $additions)) {
                $lang->display_value += $additions[$lang->language];
            }

            if (array_key_exists($lang->language, $subtractions)) {
                $lang->display_value *= $subtractions[$lang->language];
            }

            $lang->save();
        }
    }

    private function slugifyLanguage($language) {
        if (strpos($language, "+") !== false) {
            $language = str_replace("+", "plus", $language);
        }
    
        $language = preg_replace("/[^a-zA-Z0-9]+/", "-", $language);
        return strtolower($language);
    }

    private function calculateTableWidths()
    {
        $languages = Language::active()->get();
        $total = Language::active()->sum('display_value');

        foreach ($languages as $language) {
            $width = round(($language->display_value / $total) * 100, 2);
            $language->width = $width;

            $language->save();
        }
    }
}
