<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Language;

class GithubController extends Controller
{
    protected $username;
    protected $error;

    public function __construct()
    {
        $this->username = "AlextheYounga";
        $this->error = false;
    }

    public function client($url)
    {
        $oauth = "token " . env('GITHUB_PERSONAL_TOKEN');

        return Http::withBasicAuth($this->username, $oauth)
        ->withHeaders([
            'User-Agent' => $this->username,
            'Authorization' => $oauth,
            'proxy' => 'http://(ip_address):(port)',
            'accept' => 'application/vnd.github.v3+json',
        ])
        ->get($url);
    }

    public function fetchReposFromGithub()
    {
        $api_url = "https://api.github.com/search/repositories?q=user:{$this->username}";

        $repoResponse = $this->client($api_url);

        if (empty($repoResponse) || $repoResponse->failed()) {
            Log::error($repoResponse->status() . ' - ' . $repoResponse->body());
            $this->error = true;
            return $repoResponse->body();
        }

        $responseArray = json_decode($repoResponse->body(), true);
        $items = $responseArray['items'] ?? [];

        foreach($items as $repo) {
            Repository::updateOrCreate(
                [
                    'path' => $repo['full_name'],
                    'host' => 'github'
                ],
                [
                    'name' => $repo['name'],
                    'visibility' => $repo['private'] ? 'private' : 'public',
                    'properties' => [
                        'repoId' => $repo['id'],
                        'url' => $repo['html_url'],
                        'languagesUrl' => $repo['languages_url'],
                        'primaryLanguage' => $repo['language'],
                    ]
                ]
            );
            Log::info('Saved ' . $repo['name']);
        }
    }

    public function fetchRepoLanguages()
    {
        $updatedRepos = [];
        $repos = Repository::where('host', 'github')
            ->get();

        foreach($repos as $repo) {
            try {
                $properties = $repo->properties;
                $languageUrl = $repo->properties['languagesUrl'];
                $languagesResponse = $this->client($languageUrl);

                if (empty($languagesResponse) || $languagesResponse->failed()) {
                    throw new \Exception($languagesResponse->status() . ' - ' . $languagesResponse->body());
                }

                $languagesArray = json_decode($languagesResponse, true);

                $properties['languagesResponse'] = $languagesArray;
                $repo->properties = $properties;
                $repo->save();

                array_push($updatedRepos, $repo->name);

                Log::info('Updated ' . $repo->name . ' with languages ' . json_encode($languagesArray));
                sleep(0.5);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
        }

        if (empty($updatedRepos)) {
            $message = "Failed to get language stats; stats are null.";

            Log::error($message);
            $this->error = true;

            return $message;
        }
    }

    public function saveLanguageStatistics()
    {
        $repos = Repository::where('host', 'github')->get();

        foreach($repos as $repo) {
            $languages = $repo->properties['languagesResponse'];

            $repo->languages = $languages;
            $repo->save();
        }
    }


    public function runSync()
    {
        $repoReponse = $this->fetchReposFromGithub();

        if ($this->error === true) {
            return $repoReponse;
        }

        $languagesResponse = $this->fetchRepoLanguages();

        if ($this->error === true) {
            return $languagesResponse;
        }

        $this->saveLanguageStatistics();
    }
}
