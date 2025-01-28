<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Repository;
use App\Models\PageContent;

class LanguageBarController extends Controller
{
    public function setup()
    {
        $content = PageContent::where('view', '=', 'LanguageBar')
            ->get()
            ->keyBy('key');

        $languages = Language::active()
            ->orderBy('width', 'desc')
            ->get();

        // Repository size
        $repoCount = Repository::all()->count();
        $bytes = Repository::getTotalSize();
		$digits = strlen((string) $bytes);
		$scale = match ($digits) {
			4 => 'KB',
			7 => 'MB',
			10 => 'GB',
		};

		$divisor = pow(10, $digits - 1);
        $displaySize = $bytes ? round($bytes / $divisor, 2) : 0;

        $data = [
            'content' => $content,
            'languages' => $languages,
            'repoStats' => [
                'count' => $repoCount,
                'size' => $displaySize,
				'scale' => $scale
            ]
        ];

        return response()->json($data);
    }
}
