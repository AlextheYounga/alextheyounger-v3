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
        'properties' => 'json',
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
