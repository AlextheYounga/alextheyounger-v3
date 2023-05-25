<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookCategory::truncate();
        Book::truncate();

        $booksJson = file_get_contents('storage/data/books.json');
        $books = json_decode($booksJson, true);

        foreach($books as $book) {
            $new_book = Book::create([
                'title' => $book['title'],
                'author' => $book['author'],
                'description' => $book['description'],
                'image_name' => $book['image_name'],
                'external_link' => $book['external_link'],
                'external_image_link' => $book['external_image_link'] ?? null,
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
