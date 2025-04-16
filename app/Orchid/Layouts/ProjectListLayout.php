<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\Project;
use Orchid\Screen\Actions\ModalToggle;
use Carbon\Carbon;

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
            TD::make('active', 'Active')
                ->render(fn (Project $project) => (boolean) $project->active ? '✓' : '✗'),
            TD::make('title', 'Title')
                ->render(function (Project $project) {
                    return Link::make($project->title)
                        ->route('platform.project.edit', $project);
                }),
            TD::make('scope', 'Scope'),
            TD::make('excerpt', 'Excerpt')
				->render(fn (Project $project) => strlen($project->excerpt) . ' chars'),
            TD::make('description', 'Description')
				->render(fn (Project $project) => strlen($project->description) . ' chars'),
            TD::make('image_name', 'Image Name'),
            TD::make('external_link', 'External Link'),
            TD::make('external_image_link', 'Image Link')
				->render(fn (Project $project) => $project->external_image_link ? '✓' : '✗'),
            TD::make('created_at', 'Created')
                ->render(fn (Project $project) => $this->formatDate($project->created_at)),
            TD::make('updated_at', 'Last edit')
                ->render(fn (Project $project) => $this->formatDate($project->updated_at)),
        ];
    }

    private function formatDate($dateString)
    {
        $carbon = Carbon::parse($dateString);
        return $carbon->format('Y-m-d');
    }
}
