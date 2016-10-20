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
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }
}
