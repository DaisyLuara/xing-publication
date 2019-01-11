<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 多用户认证
        Broadcast::routes(["middleware" => "auth:api,customer,arMemberSession"]);

        require base_path('routes/channels.php');
    }
}
