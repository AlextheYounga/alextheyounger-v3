<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Repository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitlabController extends Controller
{
    protected $error;

    public function __construct()
    {
        $this->error = false;
    }

    public function client($url, $params)
    {
        $token = env('GITLAB_PERSONAL_TOKEN');
        $params['private_token'] = $token;

        return Http::get($url, $params);
    }

    public function fetchReposFromGitlab()
    {
        $api_url = "https://gitlab.com/api/v4/projects";
        $params = [
            'owned' => true,
            'statistics' => true
        ];

        $repoResponse = $this->client($api_url, $params);

        if (empty($repoResponse) || $repoResponse->failed()) {
            Log::error($repoResponse->status() . ' - ' . $repoResponse->body());
            $this->error = true;
            return $repoResponse->body();
        }

        $responseArray = json_decode($repoResponse->body(), true);

        foreach($responseArray as $repo) {
            Repository::updateOrCreate(
                [
                    'path' => $repo['path_with_namespace'],
                    'host' => 'gitlab'
                ],
                [
                    'name' => $repo['name'],
                    'visibility' => $repo['visibility'],
                    'properties' => [
                        'repoId' => $repo['id'],
                        'url' => $repo['_links']['self'],
                        'languagesUrl' => $repo['_links']['self'] . '/languages',
                        'statistics' => $repo['statistics'],
                    ]
                ]
            );
            Log::info('Saved ' . $repo['name']);
        }
    }

    public function fetchRepoLanguages()
    {
        $updatedRepos = [];
        $repos = Repository::where('host', 'gitlab')
            ->get();

        foreach($repos as $repo) {
            try {
                $properties = $repo->properties;
                $languageUrl = $repo->properties['languagesUrl'];
                $languagesResponse = $this->client($languageUrl, []);

                if (empty($languagesResponse) || $languagesResponse->failed()) {
                    throw new \Exception($languagesResponse->status() . ' - ' . $languagesResponse->body());
                }

                $languagesArray = json_decode($languagesResponse, true);

                $properties['languagesResponse'] = $languagesArray;
                $repo->properties = $properties;
                $repo->save();

                array_push($updatedRepos, $repo->name);

                Log::info('Updated ' . $repo->name . ' with languages ' . json_encode($languagesArray));

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
        $repos = Repository::where('host', 'gitlab')->get();

        foreach($repos as $repo) {
            $stats = $repo->properties['statistics'];
            $languages = $repo->properties['languagesResponse'];

            $languagesConvertedStats = Language::convertLanguageWeightToBytes($languages, $stats);
            $languagesAdjustedWeight = Language::suppressLanguageWeights($repo->name, $languagesConvertedStats);

            $repo->languages = $languagesAdjustedWeight;
            $repo->save();
        }
    }


    public function runSync()
    {
        $repoReponse = $this->fetchReposFromGitlab();

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
