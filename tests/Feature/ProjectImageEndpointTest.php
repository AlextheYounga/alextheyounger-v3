<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ProjectImageEndpointTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_caches_an_external_project_image(): void
    {
        Storage::fake('public');

        Http::fake([
            'https://example.com/project.jpg' => Http::response('cached-image-bytes', 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $project = Project::create([
            'title' => 'Cached Project',
            'scope' => 'Web',
            'position' => 1,
            'content' => [],
            'properties' => [],
            'external_link' => 'https://example.com',
            'external_image_link' => 'https://example.com/project.jpg',
            'active' => true,
        ]);

        $this->get("/api/projects/{$project->id}/image")
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg')
            ->assertHeader('Cache-Control', 'max-age=86400, public');

        Storage::disk('public')->assertExists("project-images/{$project->id}.jpg");
        Storage::disk('public')->assertExists("project-images/{$project->id}.json");
        Http::assertSentCount(1);
    }

    public function test_it_serves_the_cached_image_without_refetching(): void
    {
        Storage::fake('public');

        $project = Project::create([
            'title' => 'Cached Project',
            'scope' => 'Web',
            'position' => 1,
            'content' => [],
            'properties' => [],
            'external_link' => 'https://example.com',
            'external_image_link' => 'https://example.com/project.jpg',
            'active' => true,
        ]);

        Storage::disk('public')->put("project-images/{$project->id}.jpg", 'cached-image-bytes');
        Storage::disk('public')->put("project-images/{$project->id}.json", json_encode([
            'source_url' => 'https://example.com/project.jpg',
            'content_type' => 'image/jpeg',
            'image_path' => "project-images/{$project->id}.jpg",
        ], JSON_THROW_ON_ERROR));

        Http::fake();

        $this->get("/api/projects/{$project->id}/image")
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');

        Http::assertSentCount(0);
    }
}
