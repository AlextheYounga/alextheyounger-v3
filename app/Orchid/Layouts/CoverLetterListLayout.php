<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use App\Models\CoverLetter;
use Orchid\Screen\Fields\Group;
use Carbon\Carbon;

class CoverLetterListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     */
    protected $target = 'coverLetters';
    protected $coverLetterSite = 'https://resume.alexyounger.me/cover-letters/';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')->render(function (CoverLetter $coverLetter) {
                return Link::make($coverLetter->name)->route(
                    'platform.cover-letter.edit',
                    $coverLetter,
                );
            }),
            TD::make('hash', 'Hash'),
            TD::make('company', 'Company'),
            TD::make('hiring_manager', 'Hiring Manager'),
            TD::make('content', 'Content')->render(
                fn(CoverLetter $coverLetter) => strlen($coverLetter->content) . ' chars',
            ),
            TD::make('created_at', 'Created')->render(
                fn(CoverLetter $coverLetter) => $this->formatDate($coverLetter->created_at),
            ),
            TD::make()->render(
                fn(CoverLetter $coverLetter) => Group::make([
                    Link::make('View')
                        ->icon('eye')
                        ->target('_blank')
                        ->href($this->coverLetterSite . $coverLetter->hash),
                    Button::make('Duplicate')
                        ->icon('copy')
                        ->action(route('platform.cover-letter.duplicate', $coverLetter)),
                ]),
            ),
        ];
    }

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
