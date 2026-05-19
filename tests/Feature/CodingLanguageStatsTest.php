<?php

namespace Tests\Feature;

use App\Models\CodingLanguage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CodingLanguageStatsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_language_stats_compute_widths_on_demand(): void
    {
        CodingLanguage::create([
            'language' => 'PHP',
            'value' => 300,
            'display_value' => 75,
            'color' => '#777BB4',
            'active' => true,
            'project_count' => 3,
            'properties' => [],
        ]);

        CodingLanguage::create([
            'language' => 'JavaScript',
            'value' => 100,
            'display_value' => 25,
            'color' => '#F7DF1E',
            'active' => true,
            'project_count' => 2,
            'properties' => [],
        ]);

        CodingLanguage::create([
            'language' => 'Ruby',
            'value' => 50,
            'display_value' => 10,
            'color' => '#CC342D',
            'active' => false,
            'project_count' => 1,
            'properties' => [],
        ]);

        $this->getJson('/api/languages/stats')
            ->assertOk()
            ->assertJsonPath('languageStats.count', 3)
            ->assertJsonPath('languages.0.language', 'PHP')
            ->assertJsonPath('languages.0.width', 75)
            ->assertJsonPath('languages.1.language', 'JavaScript')
            ->assertJsonPath('languages.1.width', 25);
    }
}
