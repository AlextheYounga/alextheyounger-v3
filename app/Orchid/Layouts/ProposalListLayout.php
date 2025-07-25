<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Proposal;
use Orchid\Screen\Actions\Link;
use Carbon\Carbon;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;

class ProposalListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'proposals';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(function (Proposal $proposal) {
                    return Link::make($proposal->title)
                        ->route('platform.proposal.edit', $proposal);
                }),
            TD::make('client', 'Client'),
            TD::make('total', 'Total')
                ->render(fn (Proposal $proposal) => '$' . number_format($proposal->total, 2)),
            TD::make('client_agreement', 'Agreement')
                ->render(fn (Proposal $proposal) => $proposal->client_agreement ? '✓' : '✗'),
            TD::make('created_at', 'Created')
                ->render(fn (Proposal $proposal) => $this->formatDate($proposal->created_at)),
            TD::make('updated_at', 'Last edit')
                ->render(fn (Proposal $proposal) => $this->formatDate($proposal->updated_at)),
			TD::make()
				->render(fn(Proposal $proposal) => Group::make([
					Link::make('View')
						->icon('eye')
						->target('_blank')
						->href("/proposals/" . $proposal->hash),
					Button::make('Duplicate')
						->icon('copy')
						->action(route('platform.proposal.duplicate', $proposal)),
				])),
        ];
    }

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
