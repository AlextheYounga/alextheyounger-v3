<?php

namespace App\Orchid\Screens\PageContent;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Code;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\PageContent;

class PageContentEditScreen extends Screen
{
    /**
     * @var PageContent
     */
    public $pageContent;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PageContent $pageContent): iterable
    {
        return [
            'pageContent' => $pageContent,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit page content';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'Page Content';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [Button::make('Update')->icon('note')->method('update')->canSee($this->pageContent->exists)];
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
                Input::make('pageContent.id')->hidden(),

                Input::make('pageContent.name')->title('Name'),

                Input::make('pageContent.html_id')->title('HTML ID')->disabled(),

                Input::make('pageContent.view')->title('View')->disabled(),

                Input::make('pageContent.key')->title('Key')->disabled(),

                Code::make('pageContent.content')->title('Content')->language('html')->lineNumbers(),
            ]),
        ];
    }

    /**
     * @param PageContent    $pageContent
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $pageContent = PageContent::find($request->get('pageContent')['id']);

        $fields = $request->get('pageContent');

        $pageContent->fill($fields)->save();

        Alert::info('You have successfully updated the page content.');

        return redirect()->route('platform.page-content.list');
    }
}
