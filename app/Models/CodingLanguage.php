<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodingLanguage extends Model
{
    use HasFactory;

    protected static array $colors = [];

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

    public static function colorFor(string $language): ?string
    {
        $colors = static::colors();

        return $colors[$language] ?? null;
    }

    protected static function colors(): array
    {
        if (static::$colors !== []) {
            return static::$colors;
        }

        $colorsJson = storage_path('app/data/language-colors.json');
        $colors = json_decode(file_get_contents($colorsJson), true);

        return static::$colors = is_array($colors) ? $colors : [];
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }

    public function getLanguageColor()
    {
        if (array_key_exists($this->language, static::colors())) {
            return static::colors()[$this->language];
        }

        $randomColor = sprintf('#%06X', mt_rand(0, 0xffffff));

        return $randomColor;
    }
}
