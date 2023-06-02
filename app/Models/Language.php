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
            $languages = $repo->languages;
            if ($repo->host === 'github') {
                $languagesAdjustedWeight = Language::suppressLanguageWeights($repo->name, $languages);

                foreach($languagesAdjustedWeight as $lang => $value) {
                    Language::incrementOrCreate($lang, $value);
                }
            }
        }
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

    /*
    * Code packages can skew language stats.
    * For example, in Django, Tailwind and similar assets can make a Python project look mostly like CSS.
    * To suppress such data, specify a repo name and the language you want to exclude.
    */
    public static function suppressLanguageWeights($repoName, $languageStats)
    {
        $globalIgnore = ['Markdown'];
        $repoSuppressionData = [
            "hazlitt-data" => [
                "language" => "CSS",
                "suppressBy" => 0.000085,
                /*
                * Will suppress by whatever value you pass into suppressBy.
                * If a Django project has 4259089 bytes of CSS code, but only 364 bytes are yours: 364 / 4259089 = 0.000085
                */
            ],
        ];

        foreach(array_keys($languageStats) as $lang) {
            if (in_array($lang, $globalIgnore)) {
                unset($languageStats[$lang]);
            }
        }

        if (array_key_exists($repoName, $repoSuppressionData)) {
            $lang = $repoSuppressionData[$repoName]["language"];
            $suppressBy = $repoSuppressionData[$repoName]["suppressBy"];

            $languageStats[$lang] = (int)($languageStats[$lang] * $suppressBy);
        }

        return $languageStats;
    }
}
