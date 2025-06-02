<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\Resume;
use Carbon\Carbon;

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
			TD::make('duplicate', 'Duplicate')
				->render(function (Resume $resume) {
					return Link::make('Duplicate')
						->icon('copy')
						->route('platform.resume.duplicate', $resume);
				}),
        ];
    }

	public function duplicate(Resume $resume) {
		$resumeName = $resume->name;
		$duplicateParams = collect($resume)
			->except(['id', 'hash', 'created_at', 'updated_at'])
			->toArray();
		$duplicateParams['name'] = $resumeName . " (Copy)";
		$newResume = Resume::create($duplicateParams);
		return redirect()->route('platform.resume.edit', $newResume);
	}

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
