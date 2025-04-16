<?php

namespace App\Orchid\Screens\Resume;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\Resume;
use App\Models\Project;

class ResumeEditScreen extends Screen
{
    /**
     * @var Resume
     */
    public $resume;

	/**
	 * Fetch data to be displayed on the screen.
	 *
	 * Handles duplication by checking for a 'duplicate' flag in the request.
	 *
	 * @return array
	 */
	public function query(Resume $resume, Request $request): iterable
	{
		if ($request->routeIs('platform.resume.duplicate')) {
			// Create a duplicate instance with the same attributes.
			$duplicate = $resume->replicate();
			// Optionally, modify the name to indicate it's a copy.
			$duplicate->name = $duplicate->name . ' (Copy)';
			return [
				'resume' => $duplicate,
			];
		}

		return [
			'resume' => $resume,
		];
	}

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
		if (str_contains($this->resume->name, '(Copy)')) { 
			return 'Creating a duplicate resume';
		} else if ($this->resume->exists) {
			return 'Edit resume';
		} else {
			return 'Creating a new resume';
		}
    }

        /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Edit resume";
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create resume')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->resume->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->resume->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->resume->exists),
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
            Layout::rows([
				Input::make('resume.hash')
					->hidden(),

				Input::make('resume.name')
					->title('Name')
					->placeholder('Enter unique name')
					->required(),

				TextArea::make('resume.bio')
					->title('Bio')
					->rows(5)
					->placeholder('Provide a brief bio'),

				Matrix::make('resume.contacts')
				->title('Contacts')
				->columns([
					'Key' => 'key',
					'Href' => 'href',
					'Value' => 'text',
				])
				->placeholder('Enter contacts data'),

				Matrix::make('resume.references')
					->title('References')
					->columns([
						'Name' => 'name',
						'Title' => 'title',
						'Company' => 'company',
						'Location' => 'location',
						'Phone' => 'phone',
						'Email' => 'email',
					])
					->placeholder('Enter references data'),

				Matrix::make('resume.experience')
					->title('Experience')
					->columns([
						"Title" => "title",
						"Company" => "company",
						"Location" => "location",
						"Date" => "date",
						"Link" => "link",
						"Bullets" => "bullets",
					])
					->fields([
						"title" => Input::make()->width(1),
						"company" => Input::make()->width(1),
						"location" => Input::make()->width(1),
						"bullets" => Code::make()
							->language('json')
							->height('100px')
							->lineNumbers(false)
						
					])
					->placeholder('Enter experience data'),

				Relation::make('resume.projects.')
					->fromModel(Project::class, 'title')
					->multiple()
					->title('Choose which projects to include'),
            ])
        ];
    }

    /**
     * @param Resume  $resume
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $resume = Resume::where('name', $request->get('resume')['name'])->first() ?? new Resume();

        $fields = $request->get('resume');
		
		$fields['experience'] = array_map(function ($item) {
			$item['bullets'] = json_decode($item['bullets'], true);
			return $item;
		}, $fields['experience']);

        $fields['properties'] = json_decode($fields['properties'], true);

        $resume->fill($fields)
            ->save();

        Alert::info('You have successfully created a resume.');

        return redirect()->route('platform.resume.list');
    }

    /**
     * @param Resume $resume
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Resume $resume)
    {
        $resume->delete();

        Alert::info('You have successfully deleted the resume.');

        return redirect()->route('platform.resume.list');
    }
}
