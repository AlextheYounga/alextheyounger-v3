<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'color',
        'active',
        'project_count',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    protected $colors = [];

	    protected $settings = [
        'additions' => [
            'PHP' => 3000000,
            'Ruby' => 4204694,
            'JavaScript' => 611638,
        ],
        // Subtract percent from total
        'subtractions' => [
            'PHP' => 0.96, // Account for generated code I didn't write & Wordpress
            'JavaScript' => 0.88, // Every framework contains generated JavaScript code
        ],
        'ignore' => [
            'Markdown',
            'ASP.NET',
            'MDX',
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
            'Hack',
            'Twig',
            'Handlebars',
            'Liquid',
            'Smarty',
            'DIGITAL Command Language',
            'Less',
            'XSLT',
            'Makefile',
            'Roff',
            'Objective-C',
            'Jinja',
            'LOLCODE',
            'Motoko',
            'Batchfile',
            'NASL',
            'Sieve',
            'Procfile',
            'Standard ML',
            'Jupyter Notebook',
            'Svelte',
            'Java',
            'C++',
        ],
    ];

    public function __construct()
    {
        $colorsJson = base_path() . '/resources/data/language-colors.json';
        $this->colors = json_decode(file_get_contents($colorsJson), true);
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }

    public function getLanguageColor()
    {
        if (array_key_exists($this->language, $this->colors)) {
            return $this->colors[$this->language];
        }
        print 'No color found for language ' . $this->language . "\n";
        $randomColor = sprintf('#%06X', mt_rand(0, 0xffffff));
        return $randomColor;
    }

    public function slugifyLanguage()
    {
        $slug = $this->language;
        if (strpos($slug, '+') !== false) {
            $slug = str_replace('+', 'plus', $slug);
        }
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $slug);
        return strtolower($slug);
    }
}
