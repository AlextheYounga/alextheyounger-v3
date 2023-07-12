<?php

namespace App\Orchid\Layouts;

use App\Models\Category;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Actions\ModalToggle;
use Carbon\Carbon;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'categories';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('position', __('Position'))
            ->render(function (Category $category) {
                return ModalToggle::make($category->position)
                    ->modal('positionModal')
                    ->modalTitle('Category Position')
                    ->method('updatePosition')
                    ->asyncParameters([
                        'category' => $category->id,
                    ]);
            }),
            TD::make('name', 'Name')
                ->render(function (Category $category) {
                    return Link::make($category->name)
                        ->route('platform.category.edit', $category);
                }),
            TD::make('type', 'Type'),
            TD::make('created_at', 'Created')
                ->render(fn (Category $category) => $this->formatDate($category->created_at)),
            TD::make('updated_at', 'Last edit')
                ->render(fn (Category $category) => $this->formatDate($category->updated_at)),
            TD::make('active', 'Active')
                ->render(fn (Category $category) => (boolean) $category->active ? 'active' : 'inactive'),
        ];
    }

    public function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
