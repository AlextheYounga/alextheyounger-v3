<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\Resume;
use Carbon\Carbon;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;

class ResumeListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'resumes';
	protected $resumeSite = 'https://resume.alexyounger.me/';


    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->render(function (Resume $resume) {
                    return Link::make($resume->name)
                        ->route('platform.resume.edit', $resume);
                }),
            TD::make('hash', 'Hash'),
            TD::make('created_at', 'Created')
                ->render(fn (Resume $resume) => $this->formatDate($resume->created_at)),
            TD::make('updated_at', 'Last edit')
                ->render(fn (Resume $resume) => $this->formatDate($resume->updated_at)),
			TD::make()
				->render(fn(Resume $resume) => Group::make([
					Link::make('View')
						->icon('eye')
						->target('_blank')
						->href($this->resumeSite . $resume->hash),
					Button::make('Duplicate')
						->icon('copy')
						->action(route('platform.resume.duplicate', $resume)),
				])),
        ];
    }

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
