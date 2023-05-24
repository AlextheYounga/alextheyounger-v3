<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\GithubController;

class MockGithubApiService extends GithubController
{
    public function __construct()
    {
        $username = "AlextheYounga";
        $oauth = "token " . env('GITHUB_PERSONAL_TOKEN');

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

        $headers = [
            'User-Agent' => $username,
            'Authorization' => $oauth,
            'proxy' => 'http://(ip_address):(port)',
            'accept' => 'application/vnd.github.v3+json',
        ];

        Http::fake([
            // Stub a JSON response for GitHub endpoints...
            'https://api.github.com/search/repositories*' => Http::response(json_encode($repoResponse), 200, $headers),
            'https://api.github.com/repos/AlextheYounga/vix-vol-calculator*' => Http::response(json_encode($vixVolResponse), 200, $headers),
            'https://api.github.com/repos/AlextheYounga/vultron-js*' => Http::response(json_encode($vultronJsResponse), 200, $headers),
        ]);
    }
}
