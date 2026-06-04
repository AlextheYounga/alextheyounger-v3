<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProjectPageColorsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_projects_page_receives_server_side_tech_colors(): void
    {
        Project::create([
            'title' => 'Color Test Project',
            'scope' => 'Web',
            'position' => 1,
            'content' => [
                'excerpt' => 'Testing server-side colors.',
                'technology' => ['PHP', 'Unmapped Tech'],
                'bullets' => ['One'],
            ],
            'properties' => [],
            'external_link' => 'https://example.com',
            'active' => true,
        ]);

        $this->get('/projects')
            ->assertOk()
            ->assertSee('&quot;name&quot;:&quot;PHP&quot;', false)
            ->assertSee('&quot;color&quot;:&quot;#4F5D95&quot;', false)
            ->assertSee('&quot;name&quot;:&quot;Unmapped Tech&quot;', false)
            ->assertSee('#64748b', false);
    }
}
