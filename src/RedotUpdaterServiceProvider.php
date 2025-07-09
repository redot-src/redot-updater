<?php

namespace Redot\Updater;

use Illuminate\Support\ServiceProvider;

class RedotUpdaterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->commands([
            Commands\LoginCommand::class,
            Commands\DiffCommand::class,
            Commands\UpdateCommand::class,
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // ...
    }
}