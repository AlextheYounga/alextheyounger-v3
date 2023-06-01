<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\GithubController;

class MockGithubApiService extends GithubController
{
    protected $username;

    public function __construct(array $params)
    {
        $this->username = $params['username'];
        $oauth = "token " . $params['oauth'];
        

        $headers = [
            'User-Agent' => $this->username,
            'Authorization' => $oauth,
            'proxy' => 'http://(ip_address):(port)',
            'accept' => 'application/vnd.github.v3+json',
        ];

        $repoResponse = $params['response']['repo'];
        $repoResponseCode = $params['codes']['repo'];
        $projectResponseCode = $params['codes']['projects'];
        
        Http::fake([
            // Stub a JSON response for GitHub endpoints...
            'https://api.github.com/search/repositories*' => Http::response(json_encode($repoResponse), $repoResponseCode, $headers),
            'https://api.github.com/repos/AlextheYounga/vix-vol-calculator*' => Http::response(json_encode($params['response']['projects'][0]), $projectResponseCode, $headers),
            'https://api.github.com/repos/AlextheYounga/vultron-js*' => Http::response(json_encode($params['response']['projects'][1]), $projectResponseCode, $headers),
        ]);
    }
}
