<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::truncate();

        $booksJson = file_get_contents('storage/data/books.json');
        $books = json_decode($booksJson, true);

        foreach($books as $book) {
            $new_book = Book::create([
                'title' => $book['title'],
                'author' => $book['author'],
                'description' => $book['description'],
                'image' => $book['image_address'],
                'book_link' => $book['book_link'],
                'subtitle' => $book['subtitle'],
                'position' => $book['position'],
                'properties' => [
                    'image_alt' => $book['image_alt'],
                ],
            ]);

            $new_book->categories()->attach($book['book_category_id']);
        }
    }
}
