
<?php

use Tests\TestCase;
use App\Http\Services\MockGithubApiService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Language;
use App\Models\Repository;
use Illuminate\Support\Arr;

class LanguageTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setup();
    }


    public function test_calculate_language_statistics(): void
    {
        $savedRepositories = useJsonFixture('github/example-saved-github-repositories.json');
        $expectedLanguages = useJsonFixture('github/example-saved-github-languages.json');

        foreach($savedRepositories as $repo) {
            Repository::create($repo);
        }

        Language::calculateLanguageStatistics();

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
