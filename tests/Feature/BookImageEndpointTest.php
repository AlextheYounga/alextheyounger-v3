<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class BookImageEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_caches_an_external_book_image(): void
    {
        Storage::fake('public');

        Http::fake([
            'https://example.com/book.jpg' => Http::response('cached-book-bytes', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $book = Book::create([
            'title' => 'Cached Book',
            'author' => 'Alex Younger',
            'subtitle' => 'A great read',
            'position' => 1,
            'properties' => [],
            'external_link' => 'https://example.com',
            'external_image_link' => 'https://example.com/book.jpg',
            'active' => true,
        ]);

        $this->get("/api/books/{$book->id}/image")
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg')
            ->assertHeader('Cache-Control', 'max-age=86400, public');

        Storage::disk('public')->assertExists("book-images/{$book->id}.jpg");
        Storage::disk('public')->assertExists("book-images/{$book->id}.json");
        Http::assertSentCount(1);
    }

    public function test_it_serves_the_cached_book_image_without_refetching(): void
    {
        Storage::fake('public');

        $book = Book::create([
            'title' => 'Cached Book',
            'author' => 'Alex Younger',
            'subtitle' => 'A great read',
            'position' => 1,
            'properties' => [],
            'external_link' => 'https://example.com',
            'external_image_link' => 'https://example.com/book.jpg',
            'active' => true,
        ]);

        Storage::disk('public')->put("book-images/{$book->id}.jpg", 'cached-book-bytes');
        Storage::disk('public')->put("book-images/{$book->id}.json", json_encode([
            'source_url' => 'https://example.com/book.jpg',
            'content_type' => 'image/jpeg',
            'image_path' => "book-images/{$book->id}.jpg",
        ], JSON_THROW_ON_ERROR));

        Http::fake();

        $this->get("/api/books/{$book->id}/image")
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');

        Http::assertSentCount(0);
    }
}
