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
            Book::create([
                'title' => $book['title'],
                'category_id' => $book['book_category_id'],
                'author' => $book['author'],
                'description' => $book['description'],
                'image_name' => $book['image_name'],
                'external_link' => $book['external_link'],
                'external_image_link' => $book['external_image_link'] ?? null,
                'subtitle' => $book['subtitle'],
                'position' => $book['position'],
                'properties' => \json_decode($book['properties'], true),
                'active' => $book['active'] ?? true,
            ]);
        }
    }
}
