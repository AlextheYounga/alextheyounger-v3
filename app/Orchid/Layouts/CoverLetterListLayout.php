<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\CoverLetter;
use Carbon\Carbon;

class CoverLetterListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'coverLetters';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
		return [
			TD::make('name', 'Name')
				->render(function (CoverLetter $coverLetter) {
				return Link::make($coverLetter->name)
					->route('platform.cover-letter.edit', $coverLetter);
			}),
			TD::make('hash', 'Hash'),
			TD::make('company', 'Company'),
			TD::make('hiring_manager', 'Hiring Manager'),
			TD::make('content', 'Content')
				->render(fn (CoverLetter $coverLetter) => strlen($coverLetter->content) . ' chars'),
			TD::make('created_at', 'Created')
                ->render(fn (CoverLetter $coverLetter) => $this->formatDate($coverLetter->created_at)),
			TD::make('View', '')
				->render(function (CoverLetter $coverLetter) {
					$resumeSite = 'https://resume.alexyounger.me/cover-letters/';
					return Link::make('View')
						->icon('eye')
						->target('_blank')
						->href($resumeSite . $coverLetter->hash);
				}),
			TD::make('Duplicate', '')
				->render(function (CoverLetter $coverLetter) {
					return Link::make('Duplicate')
						->icon('copy')
						->route('platform.cover-letter.duplicate', $coverLetter);
				}),
		];
    }

		/**
     * This gets called from the Duplicate button, which links back to this function in platform.php
    */
	public function duplicate(CoverLetter $coverLetter) {
		$coverLetterName = $coverLetter->name;
		$duplicateParams = collect($coverLetter)
			->except(['id', 'hash', 'created_at', 'updated_at'])
			->toArray();
		$duplicateParams['name'] = $coverLetterName . " (Copy)";
		$newCoverLetter = coverLetter::create($duplicateParams);
		return redirect()->route('platform.cover-letter.edit', $newCoverLetter);
	}

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
