<?php

namespace App\Orchid\Screens\Proposal;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\Proposal;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;

class ProposalEditScreen extends Screen
{
    /**
     * @var Proposal
     */
    public $proposal;

	private $description;
    private $disclaimer;
	private $scope;
	private $techStack;

    public function __construct()
    {
		$this->description = file_get_contents(resource_path('data/example-description.txt'));
        $this->disclaimer = file_get_contents(resource_path('data/disclaimer.html'));
		$this->techStack = file_get_contents(resource_path('data/tech-stack.html'));
		$this->scope = file_get_contents(resource_path('data/scope.html'));
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Proposal $proposal): iterable
    {
        return [
            'proposal' => $proposal,
        ];
    }

	/**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
		if (str_contains($this->proposal->name, '(Copy)')) { 
			return 'Creating a duplicate proposal';
		} else if ($this->proposal->exists) {
			return 'Edit Proposal';
		} else {
			return 'Create Proposal';
		}
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create project')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->proposal->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->proposal->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->proposal->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
		$useClientAgreement = $this->proposal->properties['use_client_agreement'] ?? false;
        return [
            Layout::rows([
				Input::make('proposal.id')
					->hidden(),

                Input::make('proposal.title')
                    ->title('Title')
                    ->required(),

				Group::make([
					Input::make('proposal.client')
                    ->title('Client'),

					DateTimer::make('proposal.completion_date')
					->title('Completion Date')
					->format('Y-m-d'),
				]),

				CheckBox::make('proposal.use_client_agreement')
				->title('Use Client Agreement?')
				->value($useClientAgreement)
				->help('Do we want to include a client agreement?'),
				
				Group::make([
					CheckBox::make('proposal.client_agreement')
						->title('Client Agreement')
						->canSee($useClientAgreement)
						->disabled()
						->value($this->proposal->client_agreement ? 1 : 0)
						->help('Whether the client has agreed to the proposal'),

					Input::make('proposal.client_signature')
						->title('Client Signature')
						->canSee($useClientAgreement)
						->disabled()
						->help('Client signature'),

					Input::make('proposal.client_sign_date')
						->title('Client Signed At')
						->canSee($useClientAgreement)
						->disabled()
						->help('Client signed at'),
				])->autoWidth(),

				Quill::make('proposal.description')
					->title('Description')
					->value($this->proposal->content['description'] ?? $this->description),

				Quill::make('proposal.scope')
					->title('Scope of Work')
					->value($this->proposal->content['scope'] ?? $this->scope),

				Quill::make('proposal.technology')
					->title('Technology Stack')
					->value($this->proposal->content['technology'] ?? $this->techStack),
				
				Quill::make('proposal.disclaimer')
					->title('Disclaimer')
					->value($this->proposal->content['disclaimer'] ?? $this->disclaimer),

				Matrix::make('proposal.payment_schedule')
                    ->title('Payment Schedule')
					->value($this->proposal->content['payment_schedule'] ?? null)
                    ->columns([
                        'Milestone' => 'milestone',
						'Description' => 'description',
                        'Amount Due' => 'amount_due',
						'Date' => 'date'
                    ])
                    ->fields([
                        'milestone' => Input::make('milestone')
                            ->type('text'),
						'description' => Input::make('description')
                            ->type('text'),
						'amount_due' => Input::make('amount_due')
                            ->type('number')
                            ->step(0.01)
							->mask([
								'alias' => 'currency',
								'prefix' => ' ',
								'groupSeparator' => ' ',
								'digitsOptional' => true,
							]),
						'date' => Input::make('date'),
                    ]),

				Matrix::make('proposal.line_items')
					->title('Line Items')
					->columns([
						'Description' => 'description',
						'Price' => 'price',
					])
					->fields([
						'description' => Input::make('description')
							->type('text'),
						'price' => Input::make('price')
							->type('number')
							->step(0.01)
							->mask([
								'alias' => 'currency',
								'prefix' => ' ',
								'groupSeparator' => ' ',
								'digitsOptional' => true,
							])
					]),
            ]),
            Layout::view('platform.proposals.calculator'), // Custom calculator functionality for totals
        ];
    }

     /**
     * @param Proposal    $proposal
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
		$proposal = Proposal::where('id', $request->get('proposal')['id'])->first() ?? new Proposal();
        $fields = $request->get('proposal');
		$useClientAgreement = $fields['use_client_agreement'] ?? false;

		$record = [
            'client' => $fields['client'],
            'title' => $fields['title'],
            'content' => [
				'description' => $fields['description'] ?? null,
				'scope' => $fields['scope'] ?? null,
				'technology' => $fields['technology'] ?? null,
				'disclaimer' => $fields['disclaimer'] ?? null,
				'payment_schedule' => $fields['payment_schedule'] ?? null,
			],
            'line_items' => $fields['line_items'] ?? null,
			'total' => empty($fields['line_items']) ? 0 : $this->sumTotal($fields['line_items']),
			'completion_date' => $fields['completion_date'],
			'properties' => [
				'use_client_agreement' => $useClientAgreement,
			],
		];

        $proposal->fill($record)
            ->save();

        Alert::info('You have successfully created a proposal.');

        return redirect()->route('platform.proposal.list');
    }

    /**
     * @param Proposal $proposal
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Proposal $proposal)
    {
        $proposal->delete();

        Alert::info('You have successfully deleted the proposal.');

        return redirect()->route('platform.proposal.list');
    }

	/**
     * This gets called from the Duplicate button, which links back to this function in platform.php
    */
	public function duplicate(Proposal $proposal) {
		$proposalTitle = $proposal->title;
		$duplicateParams = collect($proposal)
			->except(['id', 'hash', 'created_at', 'updated_at'])
			->toArray();
		$duplicateParams['title'] = $proposalTitle . " (Copy)";
		$newProposal = Proposal::create($duplicateParams);
		return redirect()->route('platform.proposal.edit', $newProposal);
	}

	private function sumTotal($lineItems) {
		$total = 0;
		foreach ($lineItems as $item) {
			$total += $item['price'];
		}

		return $total;
	}
}
