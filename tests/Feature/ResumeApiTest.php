<?php

namespace Tests\Feature;

use App\Models\Resume;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ResumeApiTest extends TestCase
{
    use DatabaseMigrations;

    public function test_resume_api_includes_expertise(): void
    {
        $resume = Resume::create([
            'name' => 'API Resume',
            'bio' => 'Short bio',
            'expertise' => ['Laravel', 'Vue', 'PHP'],
            'education' => [],
            'contacts' => [],
            'references' => [],
            'experience' => [],
            'properties' => [],
        ]);

        $this->getJson('/api/resume/' . $resume->hash)
            ->assertOk()
            ->assertJsonPath('expertise', ['Laravel', 'Vue', 'PHP']);
    }
}
