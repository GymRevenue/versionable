<?php

declare(strict_types=1);

namespace CapeAndBay\Versionable;

use Illuminate\Support\ServiceProvider;

class VersionableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    public function register()
    {
    }
}
