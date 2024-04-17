<?php

namespace Braip\Abstracts;

use Braip\Abstracts\Commands\DomainGenerate;
use Braip\Abstracts\Commands\GenerateAbstracts;
use Illuminate\Support\ServiceProvider;

class AbstractsBaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DomainGenerate::class,
                GenerateAbstracts::class
            ]);
        }
    }
}