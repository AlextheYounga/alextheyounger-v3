<?php

namespace App\Orchid\Screens\Project;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Code;
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
        return "All projects";
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
                Input::make('project.position')
                    ->title('Position')
                    ->type('number')
                    ->help('Where in the list should this project sit; 1 is the top.'),

                Input::make('project.title')
                    ->title('Title')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this project.'),

                Input::make('project.external_link')
                    ->title('External Link')
                    ->placeholder('https://www.marketplacer.com/'),

                Input::make('project.external_image_link')
                    ->title('External Image Link')
                    ->placeholder('https://www.marketplacer.com/wp-content/uploads/2023/02/Marketplacer_Logo_black.png'),

                Input::make('project.image_name')
                    ->title('Local Image Name')
                    ->placeholder('marketplacer.jpg')
                    ->disabled(),

                Quill::make('project.description')
                    ->title('Description')
                    ->placeholder('Main description'),

                TextArea::make('project.excerpt')
                    ->title('Excerpt')
                    ->rows(3)
                    ->maxlength(200)
                    ->placeholder('Brief description for preview'),

                Input::make('project.framework')
                    ->title('Framework')
                    ->placeholder('PHP Laravel'),

                Input::make('project.scope')
                    ->title('Scope')
                    ->placeholder('Professional'),

                Code::make('project.properties')
                    ->title('Properties')
                    ->language('json')
                    ->lineNumbers(),

                Switcher::make('project.active')
                    ->sendTrueOrFalse()
                    ->title('Active')
            ])
        ];
    }

    /**
     * @param Project    $project
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Project $project, Request $request)
    {
        $project->fill($request->get('project'))
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
}
