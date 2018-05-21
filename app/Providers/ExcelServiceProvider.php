<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Exports\PointExport;
use App\Exports\MarketingExport;
use App\Exports\ProjectExport;

class ExcelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('point', function ($app) {
            return new PointExport($app->request);
        });

        $this->app->bind('marketing', function ($app) {
            return new MarketingExport($app->request);
        });

        $this->app->bind('project', function ($app) {
            return new ProjectExport($app->request);
        });
    }
}
