<?php

namespace App\Orchid\Screens\Proposal;

use App\Models\Proposal;
use App\Orchid\Layouts\ProposalListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ProposalListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'proposals' => Proposal::orderBy('created_at', 'desc')->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Proposals';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'All proposals';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [Link::make('Create new')->icon('pencil')->route('platform.proposal.edit')];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [ProposalListLayout::class];
    }
}
