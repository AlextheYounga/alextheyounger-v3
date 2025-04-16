<?php

namespace App\Orchid\Layouts;

use App\Models\Book;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\ModalToggle;
use Carbon\Carbon;

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
            TD::make('position', __('Position'))
                ->render(function (Book $book) {
                    return ModalToggle::make($book->position)
                        ->modal('positionModal')
                        ->modalTitle('Book Position')
                        ->method('updatePosition')
                        ->asyncParameters([
                            'book' => $book->id,
                        ]);
                }),
			TD::make('active', 'Active')
                ->render(fn (Book $book) => (boolean) $book->active ? '✓' : '✗'),
            TD::make('title', 'Title')
                ->render(function (Book $book) {
                    return Link::make($book->title)
                        ->route('platform.book.edit', $book);
                }),
            TD::make('author', 'Author'),
            TD::make('description', 'Description')
				->render(fn (Book $book) => strlen($book->description) . ' chars'),
            TD::make('image_name', 'Image Name'),
            TD::make('external_link', 'External Link')
				->render(fn (Book $book) => (boolean) $book->external_link ? '✓' : '✗'),
            TD::make('external_image_link', 'Image Link')
				->render(fn (Book $book) => (boolean) $book->external_image_link ? '✓' : '✗'),
            TD::make('created_at', 'Created')
                ->render(fn (Book $book) => $this->formatDate($book->created_at)),
            TD::make('updated_at', 'Last edit')
                ->render(fn (Book $book) => $this->formatDate($book->updated_at)),
        ];
    }

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
