<?php

namespace App\Orchid\Layouts;

use App\Models\Book;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class BookListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'books';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', 'Title')
                ->render(function (Book $book) {
                    return Link::make($book->title)
                        ->route('platform.book.edit', $book);
                }),
            TD::make('subtitle', 'Subtitle'),
            TD::make('author', 'Author'),
            TD::make('position', 'Position'),
            TD::make('description', 'Description'),
            TD::make('image_name', 'Image Name'),
            TD::make('external_link', 'External Link'),
            TD::make('external_image_link', 'External Image Link'),
            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
