<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $this->configureTestingDatabase();

        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function configureTestingDatabase(): void
    {
        $database = dirname(__DIR__) . '/database/testing.sqlite';

        if (!file_exists($database)) {
            touch($database);
        }

        putenv('DB_CONNECTION=sqlite');
        putenv("DB_DATABASE={$database}");
        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = $database;
        $_SERVER['DB_CONNECTION'] = 'sqlite';
        $_SERVER['DB_DATABASE'] = $database;
    }
}
