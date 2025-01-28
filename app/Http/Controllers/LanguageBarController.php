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
		
		switch (true) {
			case ($digits >= 4 && $digits < 7):
				$divisor = pow(10, 3);
				$scale = 'KB';
				break;
			case ($digits >= 7 && $digits < 10):
				$divisor = pow(10, 6);
				$scale = 'MB';
				break;
			case ($digits > 10):
				$divisor = pow(10, 9);
				$scale = 'GB';
				break;
			default:
				$divisor = pow(10, 9);
				$scale = 'GB';
		}

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
