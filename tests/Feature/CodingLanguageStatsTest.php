<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CodingLanguageStatsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_language_stats_use_default_data_when_database_is_empty(): void
    {
        $this->getJson('/api/languages/stats')
            ->assertOk()
            ->assertJsonPath('languageStats.count', 11)
            ->assertJsonPath('languages.0.name', 'PHP')
            ->assertJsonPath('languages.0.percentage', 38.29)
            ->assertJsonPath('languages.0.color', '#4F5D95')
            ->assertJsonPath('languages.0.properties.slug', 'php');
    }

    public function test_language_payload_can_be_stored_from_external_service_shape(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/languages', [
            'languages' => [
                'PHP' => [
                    'size' => 123,
                    'percentage' => 45.67,
                ],
                'JavaScript' => [
                    'size' => 50,
                    'percentage' => 18.25,
                ],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('coding_languages', [
            'name' => 'PHP',
            'value' => 123,
            'percentage' => 45.67,
        ]);

        $this->assertDatabaseHas('coding_languages', [
            'name' => 'JavaScript',
            'value' => 50,
            'percentage' => 18.25,
        ]);
    }
}
