<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::query()
            ->active()
            ->orderBy('position', 'asc')
            ->get();

        return ProjectResource::collection($projects);
    }

    public function show(Project $project): JsonResource
    {
        abort_unless($project->active, 404);

        return new ProjectResource($project);
    }
}
