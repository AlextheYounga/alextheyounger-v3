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

    public static function settings($name) {
        $settings = Yaml::parseFile('storage/app/language-settings.yml');
        return $settings[$name];
    }

    public static function getTotalBytes() {
        return DB::table('languages')->sum('value');
    }

    public static function incrementOrCreate($language)
    {
        $name = $language['language'];
        $record = Language::where('language', $name);

        if ($record->exists()) {
            $record->increment('value', $language['value']);
            $record->increment('display_value', $language['display_value']);
        } else {
            Language::create($language);
        }

        Log::info('Updated ' . $name . ' with value ' . $language['value']);
    }

    public static function getLanguagesWithWidths()
    {
        return Language::active()
            ->whereNotNull('width')
            ->orderBy('width', 'desc')
            ->select(['language', 'width'])
            ->get();
    }
}
