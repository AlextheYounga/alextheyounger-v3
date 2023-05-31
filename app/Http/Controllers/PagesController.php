<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Language;
use App\Models\Project;
use App\Models\Book;
use App\Models\Category;

class PagesController extends Controller
{
    public function home()
    {
        $languages = Language::getLanguagesWithWidths();
        $projects = Project::active()
            ->orderBy('position', 'asc')
            ->get();

        return Inertia::render('Home', [
            'languages' => $languages,
            'projects' => $projects,
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
