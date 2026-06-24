<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        'name',
        'value',
        'percentage',
        'active',
        'properties',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public static function colorFor(string $language): ?string
    {
        $colors = static::loadColors();

        return $colors[$language] ?? null;
    }

    public static function defaultLanguages(): Collection
    {
        $languagesJson = public_path('data/languages.json');
        $payload = json_decode(file_get_contents($languagesJson), true);

        return collect($payload['languages'] ?? [])
            ->map(function (array $language, string $name): array {
                return [
                    'name' => $name,
                    'value' => (float) ($language['size'] ?? 0),
                    'percentage' => (float) ($language['percentage'] ?? 0),
                    'color' => static::colorFor($name) ?? '#64748b',
                    'active' => true,
                    'properties' => [
                        'slug' => Str::slug($name),
                    ],
                ];
            })
            ->sortByDesc('percentage')
            ->values();
    }

    protected static function loadColors(): array
    {
        if (static::$colors !== []) {
            return static::$colors;
        }

        $colorsPath = public_path('data/language-colors.json');
        $colors = json_decode(file_get_contents($colorsPath), true);

        static::$colors = is_array($colors) ? $colors : [];

        return static::$colors;
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }
}
