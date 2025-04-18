<?php

namespace App\Orchid\Screens\Book;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\Book;
use App\Models\Category;

class BookEditScreen extends Screen
{
    /**
     * @var Book
     */
    public $book;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Book $book): iterable
    {
        return [
            'book' => $book
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->book->exists ? 'Edit book' : 'Creating a new book';
    }

        /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Reading list books";
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create book')
                ->method('createOrUpdate')
                ->icon('pencil')
                ->canSee(!$this->book->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->book->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->book->exists),
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
            Layout::rows([
                Input::make('book.title')
                    ->title('Title')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this book.'),

                Input::make('book.subtitle')
                    ->title('Subtitle')
                    ->placeholder('Attractive but mysterious subtitle')
                    ->help('Specify a short descriptive title for this book.'),

                Relation::make('book.category_id')
                    ->title('Category')
                    ->applyScope('active')
                    ->fromModel(Category::class, 'name')
                    ->value(Category::find($this->book->category_id)->name ?? null)
                    ->chunk(20),

                Input::make('book.position')
                    ->title('Position')
                    ->type('number')
                    ->help('Where in the list should this book sit; 1 is the top.'),

                Input::make('book.external_link')
                    ->title('External Link')
                    ->placeholder('https://www.amazon.com/How-Win-Friends-Influence-People'),

                Input::make('book.external_image_link')
                    ->title('External Image Link')
                    ->placeholder('https://m.media-amazon.com/images/'),

				Input::make('book.image_name')
                    ->title('Local Image Name')
					->value($this->book->properties['image_name'] ?? null)
                    ->placeholder('win_friends.jpg'),

				Input::make('book.image_alt')
                    ->title('Image Alt')
					->value($this->book->properties['image_alt'] ?? null)
                    ->placeholder('win_friends.jpg'),

                TextArea::make('book.description')
                    ->title('Description')
                    ->rows(3)
                    ->maxlength(200)
                    ->placeholder('Brief description for preview'),

                Input::make('book.author')
                    ->title('Author'),

                Switcher::make('book.active')
                    ->sendTrueOrFalse()
                    ->value($this->book->active ?? true)
                    ->title('Active')
            ])
        ];
    }

    /**
     * @param Book    $book
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $book = Book::where('title', $request->get('book')['title'])->first() ?? new Book();
        
        $fields = $request->get('book');

		$fields['properties'] = [
			'image_name' => $fields['image_name'] ?? null,
			'image_alt' => $fields['image_alt'] ?? null,
			'description' => $fields['description'] ?? null,
		];

        $book->fill($fields)
            ->reorderPositions()
            ->save();

        Alert::info('You have successfully created a book.');
        
        return redirect()->route('platform.book.list');
    }

    /**
     * @param Book $book
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Book $book)
    {
        $book->delete();

        Alert::info('You have successfully deleted the book.');

        return redirect()->route('platform.book.list');
    }
}
