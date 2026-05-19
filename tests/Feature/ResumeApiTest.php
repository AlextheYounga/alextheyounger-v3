<?php

namespace Tests\Feature;

use App\Models\Resume;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ResumeApiTest extends TestCase
{
    use DatabaseMigrations;

    public function test_resume_api_includes_skills(): void
    {
        $resume = Resume::create([
            'name' => 'API Resume',
            'bio' => 'Short bio',
            'skills' => ['Laravel', 'Vue', 'PHP'],
            'education' => [],
            'contacts' => [],
            'references' => [],
            'experience' => [],
            'properties' => [],
        ]);

        $this->getJson('/api/resume/' . $resume->hash)
            ->assertOk()
            ->assertJsonPath('skills', ['Laravel', 'Vue', 'PHP']);
    }
}
