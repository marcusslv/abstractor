<?php

namespace Codehubmvs\Abstracts\Tests;

use Codehubmvs\Abstracts\AbstractsBaseServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function getPackageProviders($app): array
    {
        return [
            AbstractsBaseServiceProvider::class,
        ];
    }
}
