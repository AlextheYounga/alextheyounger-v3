<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Book;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::all()
        ->with('categories');

        return Inertia::render('Books/Index', [
            'books' => $books,
        ]);
    }
    
}
