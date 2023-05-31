<?php

namespace App\Orchid\Screens\Book;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Orchid\Layouts\BookListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Fields\Input;

class BookListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'books' => Book::orderBy('position', 'asc')
                ->get()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Books';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All books in reading list";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.book.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            BookListLayout::class,
            Layout::modal('positionModal', [
                Layout::rows([
                    Input::make('book.position')
                    ->type('number')
                    ->title('Position')
                    ->help('Where in the list should this book sit; 1 is the top.'),
                ]),
            ])->type(Modal::TYPE_RIGHT)
                ->size(Modal::SIZE_SM)
                ->async('asyncGetBook')
        ];
    }

    /**
     * @param Book $book
     *
     * @return array
     */
    public function asyncGetBook(Book $book): array
    {
        return [
            'book' => $book,
        ];
    }

    /**
     * @param Book    $book
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePosition(Book $book, Request $request)
    {
        $book->fill($request->input('book'))
            ->reorderPositions()
            ->save();

        return redirect()->route('platform.book.list');
    }
}
