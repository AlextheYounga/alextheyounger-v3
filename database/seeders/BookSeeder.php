<?php

namespace Database\Seeders;

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

        $books = json_decode(file_get_contents('storage/app/public/books.json'), true);

        foreach($books as $book) {
            Book::create([
                'title' => $book['title'],
                'category_id' => $book['category_id'],
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
