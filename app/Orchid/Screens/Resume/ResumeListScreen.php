<?php

namespace App\Orchid\Screens\Resume;

use App\Models\Resume;
use App\Orchid\Layouts\ResumeListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ResumeListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'resumes' => Resume::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Resume';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'All Resumes';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [Link::make('Create new')->icon('pencil')->route('platform.resume.edit')];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [ResumeListLayout::class];
    }
}
