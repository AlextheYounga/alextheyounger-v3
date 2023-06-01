<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubController extends Controller
{
    protected $username;

    public function __construct()
    {
        $this->username = "AlextheYounga";
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

    public function getAllReposFromGithub()
    {
        $api_url = "https://api.github.com/search/repositories?q=user:{$this->username}";

        $response = $this->client($api_url);

        return $response;
    }

    public function fetchLanguagesFromGithub()
    {
        $statsSum = [];
        $repoResponse = $this->getAllReposFromGithub();

        if (empty($repoResponse) || $repoResponse->failed()) {
            Log::error($repoResponse->status() . ' - ' . $repoResponse->body());
            return $repoResponse->body();
        }

        $jsonResponse = json_decode($repoResponse, true);

        foreach ($jsonResponse["items"] as $repo) {
            try {
                $languageUrl = $repo["languages_url"] ?? null;

                Log::info('HTTP Attempting ' . $languageUrl);

                if (empty($languageUrl)) {
                    throw new \Exception('Failed to get language stats; language url is null.');
                }

                $languagesResponse = $this->client($languageUrl);
                
                if (empty($languagesResponse) || $languagesResponse->failed()) {
                    throw new \Exception($languagesResponse->status() . ' - ' . $languagesResponse->body());
                }

                Log::info($languageUrl . " - " . $languagesResponse->body());
    
                $languagesArray = json_decode($languagesResponse, true);
                $repoName = $repo["name"] ?? null;

                $languagesAdjustedWeight = $this->suppressLanguageWeights($repoName, $languagesArray);
    
                foreach ($languagesAdjustedWeight as $lang => $value) {
                    if (!array_key_exists($lang, $statsSum)) {
                        $statsSum[$lang] = $value;
                        continue;
                    }
                    $statsSum[$lang] += $value;
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                continue;
            }

            sleep(1);
        }

        if (empty($statsSum)) {
            Log::error("Failed to get language stats; stats are null.");
            return "Failed to get language stats; stats are null.";
        }

        foreach ($statsSum as $lang => $value) {
            Language::updateOrCreate(['language' => $lang], ['value' => $value]);
        }
    }

    private function suppressLanguageWeights($name, $langs)
    {
        /* Sometimes code from certain packages can highly skew your language stats.
        * For instance, in Django, some asset packages, like Tailwind, are pushed to the repo,
        * and suddenly your Python Django project is now treated as 90% CSS.
        * We can suppress that data here by pointing to a repo name and the language you would like to suppress.
        */

        $repoData = [
            "*" => [
                "language" => "markdown",
                "suppressBy" => 0, // Remove markdown altogether
            ],
            "hazlitt-data" => [
                "language" => "CSS",
                "suppressBy" => 0.0000021,
                /* Will suppress by whatever value you pass into suppressBy.
                * Let's say your Django project has 4259089 lines of CSS code, but only 9 of those lines are yours.
                * 9 / 4259089 = 0.0000021
                */
            ],
        ];

        if (array_key_exists($name, $repoData)) {
            $lang = $repoData[$name]["language"];
            $suppressBy = $repoData[$name]["suppressBy"];

            $langs[$lang] = (int)($langs[$lang] * $suppressBy);
        }

        return $langs;
    }
}
