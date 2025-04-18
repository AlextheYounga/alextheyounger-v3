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
			$properties = \json_decode($book['properties'], true);
			$properties['image_name'] = $book['image_name'] ?? null;
			$properties['description'] = $book['description'] ?? null;

            Book::create([
                'title' => $book['title'],
                'category_id' => $book['category_id'],
                'author' => $book['author'],
                'external_link' => $book['external_link'],
                'external_image_link' => $book['external_image_link'] ?? null,
                'subtitle' => $book['subtitle'],
                'position' => $book['position'],
                'properties' => $properties,
                'active' => $book['active'] ?? true,
            ]);
        }
    }
}
