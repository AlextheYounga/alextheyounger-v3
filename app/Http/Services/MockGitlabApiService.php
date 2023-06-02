<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\GitlabController;

class MockGitlabApiService extends GitlabController
{

    public function __construct(array $params)
    {
        $repoResponse = $params['response']['repo'];
        $repoResponseCode = $params['codes']['repo'];
        $projectResponseCode = $params['codes']['projects'];
        
        Http::fake([
            // Stub a JSON response for GitHub endpoints...
            'https://gitlab.com/api/v4/projects/44900025/languages*' => Http::response(json_encode($params['response']['projects'][0]), $projectResponseCode),
            'https://gitlab.com/api/v4/projects/38760816/languages*' => Http::response(json_encode($params['response']['projects'][1]), $projectResponseCode),
            'https://gitlab.com/api/v4/projects*' => Http::response(json_encode($repoResponse), $repoResponseCode),
        ]);
    }
}
