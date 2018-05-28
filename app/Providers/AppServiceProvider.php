<?php

namespace App\Providers;

use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use App\Http\Controllers\Admin\Project\V1\Models\AdminProject;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch;
use App\Models\User;
use App\Observers\ProjectLaunchObserver;
use App\Observers\AdminProjectObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\AdLaunchObserver;
use App\Observers\CompanyObserver;
use App\Observers\UserObserver;
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

        User::observe(UserObserver::class);
        Company::observe(CompanyObserver::class);
        AdminProject::observe(AdminProjectObserver::class);
        ProjectLaunch::observe(ProjectLaunchObserver::class);
        AdLaunch::observe(AdLaunchObserver::class);

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
