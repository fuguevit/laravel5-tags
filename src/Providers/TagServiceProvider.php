<?php

use Illuminate\Support\ServiceProvider;

class TagServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__.'/../../database/migrations') => database_path('migrations'),
            ], 'migrations');
            $this->publishes([
                realpath(__DIR__.'/../../config/tag.php') => config_path('tag.php'),
            ], 'config');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/tag.php'), 'tag');
    }
}
