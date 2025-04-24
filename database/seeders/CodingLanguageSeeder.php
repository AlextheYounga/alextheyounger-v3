<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CodingLanguage;
use App\Models\Repository;

class CodingLanguageSeeder extends Seeder
{
    protected $settings;
    protected $colors;

    public function run(): void
    {
        CodingLanguage::truncate();

        $repos = Repository::all();
        $settings = CodingLanguage::settings();
		$ignoreLanguages = $settings['ignore_languages'] ?? [];

        foreach($repos as $repo) {
            $languages = $repo->languages ?? [];

            foreach($languages as $lang => $data) {
				$language = new CodingLanguage();
				$language->language = $lang;
				$language->value = $data['size'];
				$language->display_value = $data['size']; // Not implemented
				$language->active = !in_array($lang, $ignoreLanguages);
				$language->color = $language->getLanguageColor();
				$language->properties = [
					'slug' => $language->slugifyLanguage(),
				    'parameterized' => parameterize($lang),
					'rawData' => [$lang => $data]	
				];
                $language->getProjectCount();
                $language->incrementOrCreate();
            }
        }

        CodingLanguage::runWeightAdjustments();
    }
}
