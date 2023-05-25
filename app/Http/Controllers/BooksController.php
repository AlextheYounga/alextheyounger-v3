<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Book;
use App\Models\Category;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::with('categories')
            ->get();

        $categories = Category::where('type', '=', 'Book::class')
            ->get();

        return Inertia::render('ReadingList', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }
    
}
