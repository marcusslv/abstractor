<?php

namespace Braip\Abstracts;

use Braip\Abstracts\Commands\DomainGenerate;
use Braip\Abstracts\Commands\GenerateAbstracts;
use Illuminate\Support\ServiceProvider;

class AbstractsBaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DomainGenerate::class,
                GenerateAbstracts::class,
            ]);
        }
    }
}
