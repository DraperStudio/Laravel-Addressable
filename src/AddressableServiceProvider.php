<?php

namespace BrianFaust\Addressable;

use BrianFaust\Countries\CountriesServiceProvider;
use BrianFaust\ServiceProvider\ServiceProvider;
use Illuminate\Interfaces\Foundation\Application;

class AddressableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishMigrations();

        $this->publishConfig();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        parent::register();

        $this->mergeConfig();

        $this->app->register(CountriesServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(parent::provides(), [
            CountriesServiceProvider::class,
        ]);
    }

    /**
     * Get the default package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        return 'addressable';
    }
}