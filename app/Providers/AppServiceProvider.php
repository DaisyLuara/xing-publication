<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Horizon\Horizon;
use Studio\Totem\Totem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        /**
         * @todo 用户登录页面 优化horizon的鉴权
         */
        Horizon::auth(function ($request) {
            return true;
//            return Auth::check();
        });


        /**
         * @todo 用户登录页面 优化horizon的鉴权
         */
        Totem::auth(function ($request) {
            return true;
//            return Auth::check();
        });

        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
        \App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Company::observe(\App\Observers\CompanyObserver::class);
        \App\Models\AdminProject::observe(\App\Observers\AdminProjectObserver::class);
        \App\Models\ProjectLaunch::observe(\App\Observers\ProjectLaunchObserver::class);
        \App\Models\AdLaunch::observe(\App\Observers\AdLaunchObserver::class);

        \Carbon\Carbon::setLocale('zh');
        $this->bootTowerSocialite();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404);
        });

        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });
    }

    private function bootTowerSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'tower',
            function ($app) use ($socialite) {
                $config = $app['config']['services.tower'];
                return $socialite->buildProvider(TowerProvider::class, $config);
            }
        );
    }
}
