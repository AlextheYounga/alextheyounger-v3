
<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Services\GithubLinguistService;
use Illuminate\Support\Facades\App;

class GithubLinguistServiceTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setup();
    }


    public function test_run_linguist_on_project_directories(): void
    {
        if (App::environment('production')) {
            $this->markTestSkipped(
                'This test only runs in local environments'
            );
        }

        $directoriesList = 'tests/Fixtures/example-directories.json';
        $directories = useJsonFixture('example-directories.json');

        $expectedCount = count($directories);

        $linguist = new GithubLinguistService($directoriesList);
        $statistics = $linguist->runLinguist();
        
        $this->assertCount($expectedCount, $statistics);
    }   
}
