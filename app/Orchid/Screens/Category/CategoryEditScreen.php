<?php

namespace App\Orchid\Screens\Category;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Code;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\Category;

class CategoryEditScreen extends Screen
{
    /**
     * @var Category
     */
    public $category;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Category $category): iterable
    {
        return [
            'category' => $category
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->category->exists ? 'Edit category' : 'Creating a new category';
    }

        /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Reading list categories";
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create category')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->category->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->category->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->category->exists),
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
                Input::make('category.name')
                    ->title('Name')
                    ->placeholder('Philosophy'),

                Input::make('category.type')
                    ->title('Type')
                    ->placeholder('Category::class')
                    ->help('Class for type of category'),
                    
                Code::make('category.properties')
                    ->title('Properties')
                    ->language('json')
                    ->lineNumbers(),

                Switcher::make('category.active')
                    ->sendTrueOrFalse()
                    ->title('Active')
            ])
        ];
    }

    /**
     * @param Category    $category
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $category = Category::where('name', $request->get('category')['name'])->first() ?? new Category();

        $fields = $request->get('category');

        $fields['properties'] = json_decode($fields['properties']);

        $category->fill($fields)->save();

        Alert::info('You have successfully created a category.');

        return redirect()->route('platform.category.list');
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Category $category)
    {
        $category->delete();

        Alert::info('You have successfully deleted the category.');

        return redirect()->route('platform.category.list');
    }
}
