<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodingLanguage extends Model
{
    use HasFactory;

	protected $colors = [];

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
}
