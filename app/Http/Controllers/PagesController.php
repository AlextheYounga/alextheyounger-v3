<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        return Inertia::render('Home', [
            // 'canLogin' => Route::has('login'),
            // 'canRegister' => Route::has('register'),
            'languages' => $languages,
            'projects' => Project::all(),
        ]);
    }

    public function resume()
    {
        return Inertia::render('Resume');
    }

    public function readingList()
    {
        $books = Book::with('categories')
            ->orderBy('position', 'asc')
            ->get();

        $categories = Category::where('type', '=', 'Book::class')
            ->get();

        return Inertia::render('ReadingList', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }

    public function projects()
    {
        return Inertia::render('Projects', [
            'projects' => Project::orderBy('position', 'asc')
                ->get()
        ]);
    }
}
