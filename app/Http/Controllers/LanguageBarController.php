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
        $gigabytes = $bytes ? round($bytes / 1000000000, 2) : 0;

        $data = [
            'content' => $content,
            'languages' => $languages,
            'repoStats' => [
                'count' => $repoCount,
                'size' => $gigabytes,
            ]
        ];

        return response()->json($data);
    }
}
