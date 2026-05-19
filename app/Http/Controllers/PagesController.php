<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\Project;
use App\Models\Book;
use App\Models\Category;

class PagesController extends Controller
{
    public function home()
    {
        return Inertia::render('Home');
    }

    public function readingList()
    {
        $books = Book::with('categories')->active()->orderBy('position', 'asc')->get();

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
            'projects' => Project::active()->orderBy('position', 'asc')->get(),
        ]);
    }

    public function starfield()
    {
        return Inertia::render('StarField');
    }

}
