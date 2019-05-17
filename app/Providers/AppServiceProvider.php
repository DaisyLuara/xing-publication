<?php

namespace App\Providers;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\MarketContract;
use App\Http\Controllers\Admin\Point\V1\Models\MarketShare;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunch;
use App\Http\Controllers\Admin\Project\V1\Models\AdminProject;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Ad\V1\Models\AdLaunch;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTplSchedule;
use App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords;
use App\Http\Controllers\Admin\WeChat\V1\Models\ThirdPartyUser;
use App\Models\Model;
use App\Models\User;
use App\Observers\ActivityMediaObserver;
use App\Observers\AdminCustomerObserver;
use App\Observers\MarketContractObserver;
use App\Observers\MarketObserver;
use App\Observers\MarketShareObserver;
use App\Observers\NotificationObserver;
use App\Observers\ProjectLaunchObserver;
use App\Observers\AdminProjectObserver;
use App\Observers\ProjectLaunchTplObserver;
use App\Observers\ProjectLaunchTplScheduleObserver;
use App\Observers\ShortUrlRecordObserver;
use App\Observers\ThirdPartyUserObserver;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\AdLaunchObserver;
use App\Observers\CompanyObserver;
use App\Observers\UserObserver;
use Laravel\Horizon\Horizon;
use Studio\Totem\Totem;
use App\Support\MallCoo;
use App\Models\Customer;

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

        User::observe(UserObserver::class);
        Company::observe(CompanyObserver::class);
        AdminProject::observe(AdminProjectObserver::class);
        ProjectLaunch::observe(ProjectLaunchObserver::class);
        AdLaunch::observe(AdLaunchObserver::class);
        ProjectLaunchTpl::observe(ProjectLaunchTplObserver::class);
        ProjectLaunchTplSchedule::observe(ProjectLaunchTplScheduleObserver::class);
        DatabaseNotification::observe(NotificationObserver::class);
        Customer::observe(AdminCustomerObserver::class);
        ActivityMedia::observe(ActivityMediaObserver::class);

        \Carbon\Carbon::setLocale('zh');
        $this->bootTowerSocialite();
        $this->bootMallCoo();
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

    private function bootMallCoo()
    {
        $this->app->singleton('mall_coo', function () {
            return new MallCoo(config('mall_coo.mall_id'), config('mall_coo.app_id'), config('mall_coo.public_key'), config('mall_coo.private_key'));
        });
    }
}
