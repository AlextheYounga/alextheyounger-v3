<?php

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\CategoryListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Fields\Input;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class CategoryListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Category::orderBy('position', 'asc')->get()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Categories';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All categories in reading list";
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
                ->route('platform.category.edit')
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
            CategoryListLayout::class,
            Layout::modal('positionModal', [
                Layout::rows([
                    Input::make('category.position')
                    ->type('number')
                    ->title('Position')
                    ->help('Where in the list should this category sit; 1 is the top.'),
                ]),
            ])->type(Modal::TYPE_RIGHT)
                ->size(Modal::SIZE_SM)
                ->async('asyncGetCategory')
        ];
    }

    /**
     * @param Category $category
     *
     * @return array
     */
    public function asyncGetCategory(Category $category): array
    {
        return [
            'category' => $category,
        ];
    }

    /**
     * @param Category    $category
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePosition(Category $category, Request $request)
    {
        $category->fill($request->input('category'))
            ->reorderPositions()
            ->save();

        return redirect()->route('platform.category.list');
    }
}
