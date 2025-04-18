<?php

namespace App\Orchid\Screens\Project;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Switcher;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\Project;

class ProjectEditScreen extends Screen
{
    /**
     * @var Project
     */
    public $project;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Project $project): iterable
    {
        return [
            'project' => $project
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->project->exists ? 'Edit project' : 'Creating a new project';
    }

        /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Edit project";
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
                ->canSee(!$this->project->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->project->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->project->exists),
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
                Input::make('project.title')
                    ->title('Title')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this project.'),
                
                Input::make('project.position')
                    ->title('Position')
                    ->type('number')
                    ->help('Where in the list should this project sit; 1 is the top.'),

				Input::make('project.scope')
                    ->title('Scope')
                    ->placeholder('Professional'),

                Input::make('project.external_link')
                    ->title('External Link')
                    ->placeholder('https://www.marketplacer.com/'),

                Input::make('project.external_image_link')
                    ->title('External Image Link')
                    ->placeholder('https://www.marketplacer.com/wp-content/uploads/2023/02/Marketplacer_Logo_black.png'),

                Input::make('project.image_name')
                    ->title('Local Image Name')
					->value($this->project->properties['image_name'] ?? null)
                    ->placeholder('marketplacer.jpg')
                    ->disabled(),

                Quill::make('project.description')
                    ->title('Description')
					->value($this->project->content['description'] ?? null)
                    ->placeholder('Main description'),

                TextArea::make('project.excerpt')
                    ->title('Excerpt')
					->value($this->project->content['excerpt'] ?? null)
                    ->rows(3)
                    ->maxlength(200)
                    ->placeholder('Brief description for preview'),

				Matrix::make('project.bullets')
                    ->title('Bullets')
					->value($this->listToMatrix($this->project->content['bullets'] ?? [], 'bullet'))
                    ->columns(['Bullet' => 'bullet'])
                    ->fields(['bullet' => Input::make('bullet')->type('text')]),

				Matrix::make('project.technology')
                    ->title('Tech Stack')
					->value($this->listToMatrix($this->project->content['technology'] ?? [], 'name'))
                    ->columns(['Name' => 'name'])
                    ->fields(['name' => Input::make('name')->type('text')]),

                Switcher::make('project.active')
                    ->sendTrueOrFalse()
                    ->value($this->project->active ?? true)
                    ->title('Active')
			]),
			Layout::view('platform.list-matrix'), // Custom matrix styles
        ];
    }

    /**
     * @param Project    $project
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $project = Project::where('title', $request->get('project')['title'])->first() ?? new Project();
        $fields = $request->get('project');

		$record = [
			'title' => $fields['title'],
			'external_link' => $fields['external_link'],
			'external_image_link' => $fields['external_image_link'],
			'scope' => $fields['scope'],
			'position' => $fields['position'],
			'content' => [
				'description' => $fields['description'] ?? null,
				'excerpt' => $fields['excerpt'] ?? null,
				'technology' => isset($fields['technology']) ? $this->matrixToList($fields['technology']) : [],
				'bullets' => isset($fields['bullets']) ? $this->matrixToList($fields['bullets']) : [],
			],
			'properties' => [
				'image_name' => $fields['image_name'] ?? null,
			],
			'active' => $fields['active'] ?? true,
		];

        $project->fill($record)
            ->reorderPositions()
            ->save();

        Alert::info('You have successfully created a project.');

        return redirect()->route('platform.project.list');
    }

    /**
     * @param Project $project
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Project $project)
    {
        $project->delete();

        Alert::info('You have successfully deleted the project.');

        return redirect()->route('platform.project.list');
    }

	private function listToMatrix($list, $keyBy) {
		if (empty($list)) {
			return [];
		}
		$matrix = [];
		foreach ($list as $item) {
			$matrix[] = [$keyBy => $item];
		}
		return $matrix;
	}

	private function matrixToList($matrix) {
		if ($matrix === null) {
			return [];
		}
		$list = [];
		foreach ($matrix as $item) {
			$list[] = array_values($item);
		}
		return $list;
	}
}
