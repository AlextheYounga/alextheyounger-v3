<?php

namespace App\Orchid\Screens\PageContent;

use App\Models\PageContent;
use App\Orchid\Layouts\PageContentListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class PageContentListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'pageContent' => PageContent::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Page Content';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'All Page Content';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [Link::make('Edit')->icon('pencil')->route('platform.page-content.edit')];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [PageContentListLayout::class];
    }
}
