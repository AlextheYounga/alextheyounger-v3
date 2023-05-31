<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\Project;
use Orchid\Screen\Actions\ModalToggle;

class ProjectListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'projects';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('position', __('Position'))
                ->render(function (Project $project) {
                    return ModalToggle::make($project->position)
                        ->modal('positionModal')
                        ->modalTitle('Project Position')
                        ->method('updatePosition')
                        ->asyncParameters([
                            'project' => $project->id,
                        ]);
                }),
            TD::make('title', 'Title')
                ->render(function (Project $project) {
                    return Link::make($project->title)
                        ->route('platform.project.edit', $project);
                }),
            TD::make('framework', 'Framework'),
            TD::make('excerpt', 'Excerpt'),
            TD::make('description', 'Description'),
            TD::make('image_name', 'Image Name'),
            TD::make('external_link', 'External Link'),
            TD::make('external_image_link', 'External Image Link'),
            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
