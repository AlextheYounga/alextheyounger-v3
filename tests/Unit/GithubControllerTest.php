
<?php

use Tests\TestCase;
use App\Http\Services\MockGithubApiService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Language;
use Illuminate\Support\Arr;

class GithubControllerTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setup();

        $this->controller = new MockGithubApiService();
    }

    public function test_fetch_languages_from_github(): void
    {
        $expectedLanguages = useJsonFixture('example-saved-languages.json');
        $this->controller->fetchLanguagesFromGithub();

        $languages = Language::all()->toArray();
        $this->assertDatabaseCount('languages', 6);

        foreach($languages as $index => $language) {
            $this->assertSame(
                $expectedLanguages[$index],
                Arr::except($language, ['created_at', 'updated_at'])
            );
        }
    }
}
