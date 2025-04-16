<?php

namespace App\Orchid\Layouts;

use App\Models\PageContent;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Carbon\Carbon;

class PageContentListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'pageContent';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', 'Name')
                ->render(function (PageContent $pageContent) {
                    return Link::make($pageContent->name)
                        ->route('platform.page-content.edit', $pageContent);
                }),
            TD::make('view', 'View'),
            TD::make('key', 'Key'),
            TD::make('html_id', 'HTML ID'),
            TD::make('created_at', 'Created')
                ->render(fn (PageContent $pageContent) => $this->formatDate($pageContent->created_at)),
            TD::make('updated_at', 'Last edit')
                ->render(fn (PageContent $pageContent) => $this->formatDate($pageContent->updated_at)),
        ];
    }

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
