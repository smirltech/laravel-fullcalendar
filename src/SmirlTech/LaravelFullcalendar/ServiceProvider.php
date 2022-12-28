<?php namespace SmirlTech\LaravelFullcalendar;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('laravel-fullcalendar', function ($app) {
            return $app->make('SmirlTech\LaravelFullcalendar\Calendar');
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views/', 'fullcalendar');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['laravel-fullcalendar'];
    }

}

