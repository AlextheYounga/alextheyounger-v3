<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
