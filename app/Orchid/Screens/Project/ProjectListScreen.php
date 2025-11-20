<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Orchid\Layouts\ProjectListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Fields\Input;
use Illuminate\Http\Request;

class ProjectListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'projects' => Project::orderBy('position', 'asc')->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Projects';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'All projects';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [Link::make('Create new')->icon('pencil')->route('platform.project.edit')];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ProjectListLayout::class,
            Layout::modal('positionModal', [
                Layout::rows([
                    Input::make('project.position')
                        ->type('number')
                        ->title('Position')
                        ->help('Where in the list should this project sit; 1 is the top.'),
                ]),
            ])
                ->type(Modal::TYPE_RIGHT)
                ->size(Modal::SIZE_SM)
                ->async('asyncGetProject'),
        ];
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function asyncGetProject(Project $project): array
    {
        return [
            'project' => $project,
        ];
    }

    /**
     * @param Project    $project
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePosition(Project $project, Request $request)
    {
        $project->fill($request->input('project'))->reorderPositions()->save();

        return redirect()->route('platform.project.list');
    }
}
