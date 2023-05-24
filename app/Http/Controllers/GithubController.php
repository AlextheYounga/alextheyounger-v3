<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Support\Facades\Http;

class GithubController extends Controller
{
    protected $username;
    protected $oauth;

    public function __construct()
    {
        $this->username = "AlextheYounga";
    }

    public function client($url)
    {
        $username = "AlextheYounga";
        $oauth = "token " . env('GITHUB_PERSONAL_TOKEN');

        return Http::withBasicAuth($username, $oauth)
        ->withHeaders([
            'User-Agent' => $username,
            'Authorization' => $oauth,
            'proxy' => 'http://(ip_address):(port)',
            'accept' => 'application/vnd.github.v3+json',
        ])
        ->get($url);
    }

    public function getAllRepos()
    {
        $api_url = "https://api.github.com/search/repositories?q=user:{$this->username}";

        $response = $this->client($api_url);

        return $response->body();
    }

    public function getRepoStats($langUrl)
    {
        $response = $this->client($langUrl);

        $langResponse = $response->body();

        return json_decode($langResponse, true);
    }

    public function fetchLanguagesFromGithub()
    {
        $statsSum = [];
        $repoResponse = $this->getAllRepos();

        if (!empty($repoResponse)) {
            $jsonResponse = json_decode($repoResponse, true);

            foreach ($jsonResponse["items"] as $repo) {
                $languageUrl = $repo["languages_url"] ?? null;
                $repoName = $repo["name"] ?? null;

                if (!empty($languageUrl)) {
                    $langs = $this->getRepoStats($languageUrl);

                    if (!empty($langs)) {
                        $langs = $this->suppressLanguages($repoName, $langs);

                        foreach ($langs as $lang => $value) {
                            if (!array_key_exists($lang, $statsSum)) {
                                $statsSum[$lang] = $value;
                                continue;
                            }
                            $statsSum[$lang] += $value;
                        }
                    }
                    sleep(1);
                }
            }
        }

        if (empty($statsSum)) {
            throw new \Exception("Failed to get language stats; stats are nil.");
        }

        foreach ($statsSum as $lang => $value) {
            Language::updateOrCreate(['language' => $lang], ['value' => $value]);
        }
    }

    private function suppressLanguages($name, $langs)
    {
        /* Sometimes code from certain packages can highly skew your language stats.
        * For instance, in Django, some asset packages, like Tailwind, are pushed to the repo,
        * and suddenly your Python Django project is now treated as 90% CSS.
        * We can suppress that data here by pointing to a repo name and the language you would like to suppress.
        */

        $repoData = [
            "hazlitt-data" => [
                "lang" => "CSS",
                "suppressBy" => 0.0000021,
                /* Will suppress by whatever value you pass into suppressBy.
                * Let's say your Django project has 4259089 lines of CSS code, but only 9 of those lines are yours.
                * 9 / 4259089 = 0.0000021
                */
            ],
        ];

        if (array_key_exists($name, $repoData)) {
            $lang = $repoData[$name]["lang"];
            $suppressBy = $repoData[$name]["suppressBy"];

            $langs[$lang] = (int)($langs[$lang] * $suppressBy);
        }

        return $langs;
    }
}
