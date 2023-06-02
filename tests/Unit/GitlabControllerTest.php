
<?php

use Tests\TestCase;
use App\Http\Services\MockGitlabApiService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Language;
use App\Models\Repository;
use Illuminate\Support\Arr;

class GitlabControllerTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setup();
    }

    public function test_fetch_languages_from_github(): void
    {
        $repoResponse = useJsonFixture('gitlab/gitlab-projects-response-example.json');
        $gptLanguagesResponse = useJsonFixture('gitlab/gitlab-languages-gpt-response.json');
        $wwLanguagesResponse = useJsonFixture('gitlab/gitlab-languages-ww-response.json');

        $apiParams = [
            'response' => [
                'repo' => $repoResponse,
                'projects' => [
                    $gptLanguagesResponse, 
                    $wwLanguagesResponse
                ]
            ],
            'codes' => [
                'repo' => 200,
                'projects' => 200
            ]
        ];

        $controller = new MockGitlabApiService($apiParams);

        $expectedRepositories = useJsonFixture('gitlab/example-saved-gitlab-repositories.json');
        $expectedLanguages = useJsonFixture('gitlab/example-saved-gitlab-languages.json');

        $controller->runSync();

        $repositories = Repository::all()->toArray();
        $languages = Language::all()->toArray();

        $this->assertDatabaseCount('repositories', 2);
        $this->assertDatabaseCount('languages', 5);

        foreach($repositories as $index => $repo) {
            $this->assertSame(
                $expectedRepositories[$index],
                Arr::except($repo, ['created_at', 'updated_at'])
            );
        }

        foreach($languages as $index => $language) {
            $this->assertSame(
                $expectedLanguages[$index],
                Arr::except($language, ['created_at', 'updated_at'])
            );
        }
    }

    public function test_fail_to_fetch_repos_from_github(): void
    {
        $repoResponse = [
            "message" => "API rate limit exceeded for xxx.xxx.xxx.xxx. (But here's the good news: Authenticated requests get a higher rate limit. Check out the documentation for more details.)",
            "documentation_url" => "https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"
        ];

        $apiParams = [
            'response' => [
                'repo' => $repoResponse,
                'projects' => [
                    $repoResponse, 
                    $repoResponse,
                ]
            ],
            'codes' => [
                'repo' => 403,
                'projects' => 500
            ]
        ];

        $controller = new MockGitlabApiService($apiParams);
        $response = $controller->runSync();

        $this->assertSame(json_encode($repoResponse), $response);
    }

    public function test_fail_to_fetch_project_from_github(): void
    {

        $repoResponse = useJsonFixture('gitlab/gitlab-projects-response-example.json');

        $projectResponse = [
            "message" => "API rate limit exceeded for xxx.xxx.xxx.xxx. (But here's the good news: Authenticated requests get a higher rate limit. Check out the documentation for more details.)",
            "documentation_url" => "https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"
        ];

        $expectedResponse = "Failed to get language stats; stats are null.";

        $apiParams = [
            'response' => [
                'repo' => $repoResponse,
                'projects' => [
                    $projectResponse, 
                    $projectResponse,
                ]
            ],
            'codes' => [
                'repo' => 200,
                'projects' => 403
            ]
        ];

        $controller = new MockGitlabApiService($apiParams);
        $response = $controller->runSync();

        $this->assertSame($response, $expectedResponse);
    }
}
