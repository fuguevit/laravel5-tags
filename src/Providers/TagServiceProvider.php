<?php

namespace Fuguevit\Tags\Providers;

use Illuminate\Support\ServiceProvider;

class TagServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/../../database/migrations/') => database_path('migrations'),
        ], 'migrations');
        $this->publishes([
            realpath(__DIR__.'/../../config/tag.php') => config_path('tag.php'),
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/tag.php'), 'tag');
    }
}
