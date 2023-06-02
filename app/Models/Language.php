<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language',
        'value',
    ];

    public static function getLanguagesWithWidths()
    {
        $langs = Language::orderBy('value', 'desc')->get();
        $values = Language::pluck('value');

        if ($langs->isNotEmpty()) {
            $total = $values->sum();
            $widths = [];

            foreach ($langs as $lang) {
                if (is_float($lang->value) && is_float($total) && $lang->value > 0 && $total > 0) {
                    $width = round(($lang->value / $total) * 100, 2);
                    $widths[$lang->language] = $width;
                }
            }

            return $widths;
        }
    }

    public static function calculateLanguageStatistics()
    {
        Language::truncate();
        $repos = Repository::all();

        foreach($repos as $repo) {
            $languages = $repo->languages ?? [];
            $languagesAdjustedWeight = Language::suppressLanguageWeights($repo->name, $languages);

            foreach ($languagesAdjustedWeight as $lang => $value) {
                Language::incrementOrCreate($lang, $value);
            }
        }

        Language::adjustLanguageWeights();
    }

    public static function incrementOrCreate($language, $value)
    {
        if (Language::where('language', $language)->exists()) {
            Language::where('language', $language)->increment('value', $value);
        } else {
            Language::create([
                'language' => $language,
                'value' => $value,
            ]);
        }

        Log::info('Updated ' . $language . ' with value ' . $value);
    }

    public static function convertLanguageWeightToBytes($languages, $stats)
    {
        $convertedStats = [];
        $repoSize = $stats['repository_size'];

        foreach($languages as $lang => $value) {
            $percentileRank = $value / 100;
            $bytes = $percentileRank * $repoSize;
            $convertedStats[$lang] = (int) $bytes;
        }

        return $convertedStats;
    }

    private static function adjustLanguageWeights()
    {
        // Accounting for repos I can't legally keep on my computer.
        $additions = [
            'Ruby' => 4000000, 
            'PHP' => 3000000
        ];

        // Arbitrary adjustments to better represent the realities of my framework generated code.
        $subtractions = [
            'JavaScript' => 0.5, // Every framework contains generated JavaScript code.
            'HTML' => 0.3, // Many frameworks contain generated HTML code.
            'Vue' => 0.7, // Inertia and Breeze generate some Vue code.
        ];

        $languages = Language::all();

        foreach($languages as $lang) {
            if (array_key_exists($lang->language, $additions)) {
                $lang->value += $additions[$lang->language];
            }

            if (array_key_exists($lang->language, $subtractions)) {
                $lang->value *= $subtractions[$lang->language];
            }
            $lang->save();
        }

    }

    /*
    * Accounting for generated code that I want to suppress from the statistics.
    */
    private static function suppressLanguageWeights($repoName, $languageStats)
    {
        $globalIgnore = ['Markdown', 'ASP.NET'];

        /*
        * Will suppress by whatever value you pass into suppressBy.
        * If a Django project has 4259089 bytes of CSS code, but only 364 bytes are yours: 364 / 4259089 = 0.000085
        */
        $repoSuppressionData = [
            "hazlitt-data" => [
                "language" => "CSS",
                "suppressBy" => 0.000085,
            ],
        ];

        foreach(array_keys($languageStats) as $lang) {
            if (in_array($lang, $globalIgnore)) {
                unset($languageStats[$lang]);
            }
        }

        if (array_key_exists($repoName, $repoSuppressionData)) {
            $lang = $repoSuppressionData[$repoName]["language"];

            if (array_key_exists($lang, $languageStats)) {
                $suppressBy = $repoSuppressionData[$repoName]["suppressBy"];
                $languageStats[$lang] = (int) ($languageStats[$lang] * $suppressBy);
            }
        }

        return $languageStats;
    }
}
