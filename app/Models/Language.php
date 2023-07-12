<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Yaml\Yaml;

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
        'display_value',
        'width',
        'color',
        'active',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json'
    ];

    public function scopeActive()
    {
        return $this->where('active', true);
    }

    public function incrementOrCreate()
    {
        $record = Language::where('language', $this->language);

        if ($record->exists()) {
            $record->increment('value', $this->value);
            $record->increment('display_value', $this->display_value);
        } else {
            $this->save();
        }

        Log::info('Updated ' . $this->language . ' with value ' . $this->value);
    }

    public function runRepoSpecificValueAdjustments($repoName)
    {
        $repoSettings = Language::settings('repo-specific');

        if (array_key_exists($repoName, $repoSettings)) {
            $suppressLanguage = $repoSettings[$repoName]["language"];

            if ($suppressLanguage === $this->language) {
                $suppressBy = $repoSettings[$repoName]["suppressBy"];
                $this->display_value = (int) ($this->value * $suppressBy);
            }
        }
    }

    public static function settings($name) {
        $settings = Yaml::parseFile('storage/app/language-settings.yml');
        return $settings[$name];
    }

    public static function getLanguageColor($language) {
        $colors = json_decode(file_get_contents('resources/data/language-colors.json'), true);
        return $colors[$language];
    }

    public static function getTotalBytes() {
        return DB::table('languages')->sum('value');
    }

    public static function runWeightAdjustments()
    {
        $languages = Language::all();

        $additions = Language::settings('additions');
        $subtractions = Language::settings('subtractions');

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

    public static function slugifyLanguage($language) {
        if (strpos($language, "+") !== false) {
            $language = str_replace("+", "plus", $language);
        }
    
        $language = preg_replace("/[^a-zA-Z0-9]+/", "-", $language);
        return strtolower($language);
    }

    public static function calculateTableWidths()
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
