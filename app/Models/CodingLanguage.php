<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Repository;

class CodingLanguage extends Model
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

	protected $colors;

	public function __construct()
	{
		$colorsJson = base_path() . '/resources/data/language-colors.json';
		$this->colors = json_decode(file_get_contents($colorsJson), true);
	}


    public function scopeActive()
    {
        return $this->where('active', true);
    }

    public function incrementOrCreate()
    {
        $record = CodingLanguage::where('language', $this->language);

        if ($record->exists()) {
            $record->increment('value', $this->value);
            $record->increment('display_value', $this->display_value);
        } else {
            $this->save();
        }

        Log::info('Updated ' . $this->language . ' with value ' . $this->value);
    }

    public function getProjectCount()
    {
        $count = 0;
        $repos = Repository::pluck('languages')->toArray();
        foreach ($repos as $repo) {
            if (array_key_exists($this->language, $repo)) {
                $count++;
            }
        }

        $this->project_count = $count;
    }

	public function getLanguageColor()
    {
        if (array_key_exists($this->language, $this->colors)) {
            return $this->colors[$this->language];
        }
        print('No color found for language ' . $this->language . "\n");
        $randomColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        return $randomColor;
    }

	public function slugifyLanguage()
    {
		$slug = $this->language;
        if (strpos($slug, "+") !== false) {
            $slug = str_replace("+", "plus", $slug);
        }
        $slug = preg_replace("/[^a-zA-Z0-9]+/", "-", $slug);
        return strtolower($slug);
    }

	public static function settings() {
		return [
			'additions' => [
				'PHP' => 3000000,
				'Ruby' => 4204694,
				'JavaScript' => 611638,
			],
			'subtractions' => [
				'Ruby' => 0.5, // Account for code I didn't write
				'PHP' => 0.5, // Account for code I didn't write
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
				'Nix'
			]
		];
	}

	// TODO: Turn this into an instance method
    public static function runWeightAdjustments()
    {
        $languages = CodingLanguage::all();
		$settings = CodingLanguage::settings();

        foreach($languages as $lang) {
            if (array_key_exists($lang->language, $settings['additions'])) {
                $lang->display_value += $settings['additions'][$lang->language];
            }

            if (array_key_exists($lang->language, $settings['subtractions'])) {
                $lang->display_value *= $settings['subtractions'][$lang->language];
            }

            $lang->save();
        }

		return CodingLanguage::calculateTableWidths();
    }

    public static function calculateTableWidths()
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