<?php

namespace App\Providers;

use App\Exports\MarketingExport;
use App\Exports\MarketingTopExport;
use App\Exports\OldMarketingExport;
use App\Exports\PointDailyAverageExport;
use App\Exports\PointExport;
use App\Exports\ProjectByPointExport;
use App\Exports\ProjectExport;
use Illuminate\Support\ServiceProvider;

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

        $this->app->bind('daily_average', function ($app) {
            return new PointDailyAverageExport($app->request);
        });

        $this->app->bind('project_point', function ($app) {
            return new ProjectByPointExport($app->request);
        });

        $this->app->bind('marketing_top', function ($app) {
            return new MarketingTopExport($app->request);
        });
        $this->app->bind('old_marketing', function ($app) {
            return new OldMarketingExport($app->request);
        });
    }
}
