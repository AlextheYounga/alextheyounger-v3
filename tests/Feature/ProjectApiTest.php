<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class ProjectApiTest extends TestCase
{
    use DatabaseMigrations;

    public function test_projects_api_returns_only_active_projects_in_position_order(): void
    {
        $inactiveProject = Project::create([
            'title' => 'Inactive Project',
            'scope' => 'Web',
            'position' => 1,
            'content' => ['description' => 'Hidden'],
            'properties' => [],
            'external_link' => 'https://example.com/inactive',
            'external_image_link' => 'https://example.com/inactive.jpg',
            'active' => false,
        ]);

        $laterProject = Project::create([
            'title' => 'Later Project',
            'scope' => 'Mobile',
            'position' => 20,
            'content' => ['description' => 'Later'],
            'properties' => ['featured' => true],
            'external_link' => 'https://example.com/later',
            'external_image_link' => 'https://example.com/later.jpg',
            'active' => true,
        ]);

        $earlierProject = Project::create([
            'title' => 'Earlier Project',
            'scope' => 'API',
            'position' => 10,
            'content' => ['description' => 'Earlier'],
            'properties' => ['featured' => false],
            'external_link' => 'https://example.com/earlier',
            'external_image_link' => 'https://example.com/earlier.jpg',
            'active' => true,
        ]);

        $this->getJson('/api/projects')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $earlierProject->id)
            ->assertJsonPath('data.0.title', 'Earlier Project')
            ->assertJsonPath('data.1.id', $laterProject->id)
            ->assertJsonMissing(['id' => $inactiveProject->id]);
    }

    public function test_projects_api_exposes_project_fields(): void
    {
        $project = Project::create([
            'title' => 'API Project',
            'scope' => 'Web',
            'position' => 5,
            'content' => ['description' => 'Project description'],
            'properties' => ['client' => 'Internal'],
            'external_link' => 'https://example.com/project',
            'external_image_link' => 'https://example.com/project.jpg',
            'active' => true,
        ]);

        $this->getJson('/api/projects/' . $project->id)
            ->assertOk()
            ->assertJsonPath('data.id', $project->id)
            ->assertJsonPath('data.external_link', 'https://example.com/project')
            ->assertJsonPath('data.content.description', 'Project description')
            ->assertJsonPath('data.properties.client', 'Internal')
            ->assertJsonPath('data.active', true);
    }

    public function test_projects_api_returns_404_for_inactive_projects(): void
    {
        $project = Project::create([
            'title' => 'Hidden Project',
            'scope' => 'Web',
            'position' => 5,
            'content' => ['description' => 'Hidden'],
            'properties' => [],
            'external_link' => 'https://example.com/hidden',
            'external_image_link' => 'https://example.com/hidden.jpg',
            'active' => false,
        ]);

        $this->getJson('/api/projects/' . $project->id)
            ->assertNotFound();
    }
}
