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

app('Dingo\Api\Exception\Handler')->register(function (Exception $exception) {
    if ($exception instanceof TokenExpiredException) {
        return response()->json('未登录', 401);
    };
});

include app_path('Http/Controllers/Admin/Ad/V1/routes.php');
include app_path('Http/Controllers/Admin/Common/V1/routes.php');
include app_path('Http/Controllers/Admin/Company/V1/routes.php');
include app_path('Http/Controllers/Admin/Device/V1/routes.php');
include app_path('Http/Controllers/Admin/Point/V1/routes.php');
include app_path('Http/Controllers/Admin/Face/V1/routes.php');
include app_path('Http/Controllers/Admin/Project/V1/routes.php');
include app_path('Http/Controllers/Admin/User/V1/routes.php');
include app_path('Http/Controllers/Admin/WeChat/V1/routes.php');
include app_path('Http/Controllers/Admin/Auth/V1/routes.php');
include app_path('Http/Controllers/Admin/ShortUrl/V1/routes.php');
include app_path('Http/Controllers/Admin/Attribute/V1/routes.php');
