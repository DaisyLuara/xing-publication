<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('version', function () {
        return response('this is version v1');
    });
});

$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1\GizWits',], function ($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        $api->group(['middleware' => 'api.auth', 'prefix' => 'gizwits'], function ($api) {

            //用户管理
            $api->get('users', ['uses' => 'UserController@index',]);
            $api->get('tags/user', ['uses' => 'AppController@getTagUsers',]);

            //远程监控
            $api->get('app/start/{did}', ['uses' => 'AppController@start',]);
            $api->get('app/stop/{did}', ['uses' => 'AppController@stop',]);
            $api->get('app/restart/{did}', ['uses' => 'AppController@restart',]);
            $api->get('app/devices/{did}', ['uses' => 'AppController@deviceDetail',]);
            $api->any('app/{did}/latest', ['uses' => 'AppController@latest',]);

            //绑定管理
            $api->any('app/bindings', ['uses' => 'AppController@bindings',]);

            //任务调度
            $api->any('app/warning', ['uses' => 'AppController@warning',]);
            $api->post('app/schedule', ['uses' => 'AppController@schedule']);
        });
    });

});
