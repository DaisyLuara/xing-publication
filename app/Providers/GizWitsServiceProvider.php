<?php

namespace App\Providers;

use App\Libraries\GizWits\GizWitsClient;
use Illuminate\Support\ServiceProvider;

class GizWitsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $giz_wits = $this->app->get('giz_wits');
        $giz_wits->setUserToken();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('giz_wits', function ($app) {
            $config = config('giz_wits.'.env('APP_ENV'));
            return new GizWitsClient($config['app_id'], $config['username'],$config['password'],$config['attr'],$config['open_ids'],$config['template_id']);
        });
    }
}
