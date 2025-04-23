<?php

namespace App\Orchid\Screens\CoverLetter;

use App\Models\CoverLetter;
use App\Orchid\Layouts\CoverLetterListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CoverLetterListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'coverLetters' => CoverLetter::all()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Cover Letter';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All Cover Letters";
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
                ->route('platform.cover-letter.edit')
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
            CoverLetterListLayout::class,
        ];
    }

}
