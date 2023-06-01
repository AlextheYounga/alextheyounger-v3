
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

    protected function setUp(): void
    {
        parent::setup();
    }
    

    public function test_fetch_languages_from_github(): void
    {
        $repoResponse = useJsonFixture('github-repo-response-example.json');

        $vixVolResponse = [
            "Python" => 26914,
            "Shell" => 19
        ];

        $vultronJsResponse = [
            "Vue" => 38045,
            "JavaScript" => 35576,
            "HTML" => 611,
            "CSS" => 61,
            "Shell" => 25
        ];

        $apiParams = [
            'username' => 'AlextheYounga',
            'oauth' => 'SOMETOKEN',
            'response' => $repoResponse,
            'response' => [
                'repo' => $repoResponse,
                'projects' => [
                    $vixVolResponse, 
                    $vultronJsResponse
                ]
            ],
            'codes' => [
                'repo' => 200,
                'projects' => 200
            ]
        ];

        $controller = new MockGithubApiService($apiParams);
        $expectedLanguages = useJsonFixture('example-saved-languages.json');

        $controller->fetchLanguagesFromGithub();

        $languages = Language::all()->toArray();
        $this->assertDatabaseCount('languages', 6);

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
            'username' => 'AlextheYounga',
            'oauth' => 'SOMETOKEN',
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

        $controller = new MockGithubApiService($apiParams);
        $response = $controller->fetchLanguagesFromGithub();

        $this->assertSame(json_encode($repoResponse), $response);
    }

    public function test_fail_to_fetch_project_from_github(): void
    {

        $repoResponse = useJsonFixture('github-repo-response-example.json');

        $projectResponse = [
            "message" => "API rate limit exceeded for xxx.xxx.xxx.xxx. (But here's the good news: Authenticated requests get a higher rate limit. Check out the documentation for more details.)",
            "documentation_url" => "https://docs.github.com/rest/overview/resources-in-the-rest-api#rate-limiting"
        ];

        $expectedResponse = "Failed to get language stats; stats are null.";

        $apiParams = [
            'username' => 'AlextheYounga',
            'oauth' => 'SOMETOKEN',
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

        $controller = new MockGithubApiService($apiParams);
        $response = $controller->fetchLanguagesFromGithub();

        $this->assertSame($response, $expectedResponse);
    }
}
