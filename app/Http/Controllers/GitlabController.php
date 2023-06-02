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
                        'languages_url' => $repo['_links']['self'] . '/languages',
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
                $languageUrl = $repo->properties['languages_url'];
                $languagesResponse = $this->client($languageUrl, []);

                if (empty($languagesResponse) || $languagesResponse->failed()) {
                    throw new \Exception($languagesResponse->status() . ' - ' . $languagesResponse->body());
                }

                $languagesArray = json_decode($languagesResponse, true);

                $repo->languages = $languagesArray;
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

        $this->calculateLanguageStatistics();
    }

    private function calculateLanguageStatistics()
    {
        $languageStats = [];
        $repos = Repository::where('host', 'gitlab')->get();

        foreach($repos as $repo) {
            $stats = $repo->properties['statistics'];
            $languages = $repo->languages;

            $languagesConvertedStats = $this->convertLanguageWeightToBytes($languages, $stats);
            $languagesAdjustedWeight = Language::suppressLanguageWeights($repo->name, $languagesConvertedStats);

            foreach ($languagesAdjustedWeight as $lang => $value) {
                if (!array_key_exists($lang, $languageStats)) {
                    $languageStats[$lang] = $value;
                    continue;
                }
                $languageStats[$lang] += $value;
            }
        }

        foreach ($languageStats as $lang => $value) {
            Language::updateOrCreate(['language' => $lang], ['value' => $value]);
            Log::info('Updated ' . $lang . ' with value ' . $value);
        }
    }


}
