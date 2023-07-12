<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Language;
use App\Models\Repository;
use App\Models\Project;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;


class PagesController extends Controller
{
    public function home()
    {
        $languages = Language::active()
            ->orderBy('width', 'desc')
            ->get();
            
        $projects = Project::active()
            ->orderBy('position', 'asc')
            ->get();

        $repoCount = Repository::all()->count();

        $bytes = Language::getTotalBytes();
        $megabytes = $bytes ? round($bytes / 1000000, 2) : 0;

        return Inertia::render('Home', [
            'languages' => $languages,
            'projects' => $projects,
            'repoStats' => [
                'count' => $repoCount,
                'size' => $megabytes,
            ]
        ]);
    }

    public function resume()
    {
        return Inertia::render('Resume');
    }

    public function readingList()
    {
        $books = Book::with('categories')
            ->active()
            ->orderBy('position', 'asc')
            ->get();

        $categories = Category::active()
            ->where('type', '=', 'Book::class')
            ->orderBy('position', 'asc')
            ->get();

        return Inertia::render('ReadingList', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }

    public function projects()
    {
        return Inertia::render('Projects', [
            'projects' => Project::active()
                ->orderBy('position', 'asc')
                ->get()
        ]);
    }

    public function terrainPlayground()
    {
        return Inertia::render('TerrainPlayground');
    }
}
