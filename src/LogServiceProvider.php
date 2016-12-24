<?php

namespace WebModularity\LaravelLog;

use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot() {
        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Observers
        LogUserAgent::observe(LogUserAgentObserver::class);
    }
}