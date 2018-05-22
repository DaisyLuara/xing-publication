<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17-6-14
 * Time: 下午4:56
 */

namespace app\Listeners;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{

    /**
     * 处理用户登录事件。
     */
    public function onUserLogin($event)
    {
        $user = $event->user;

        //刷新tower access token
        $userToken = Socialite::driver('tower')
            ->stateless()
            ->refresh($user->tower_access_token, $user->tower_refresh_token);

        $user->update(['tower_access_token' => $userToken['access_token'], 'tower_refresh_token' => $userToken['refresh_token']]);
    }

    /**
     * 处理用户注销事件。
     */
    public function onUserLogout($event)
    {

    }

    /**
     * @param $event
     */
    public function onFailed($event)
    {
    }

    /**
     * 为订阅者注册监听器。
     *
     * @param  Illuminate\Events\Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Failed',
            'App\Listeners\UserEventSubscriber@onFailed'
        );

        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }
}