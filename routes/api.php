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
include app_path('Http/Controllers/Admin/Cards/V1/routes.php');
include app_path('Http/Controllers/Admin/Device/V1/routes.php');
include app_path('Http/Controllers/Admin/Point/V1/routes.php');
include app_path('Http/Controllers/Admin/Face/V1/routes.php');
include app_path('Http/Controllers/Admin/Project/V1/routes.php');
include app_path('Http/Controllers/Admin/User/V1/routes.php');
include app_path('Http/Controllers/Admin/WeChat/V1/routes.php');
include app_path('Http/Controllers/Admin/Auth/V1/routes.php');
include app_path('Http/Controllers/Admin/ShortUrl/V1/routes.php');
include app_path('Http/Controllers/Admin/Attribute/V1/routes.php');
include app_path('Http/Controllers/Admin/Pay/V1/routes.php');
include app_path('Http/Controllers/Admin/Coupon/V1/routes.php');
include app_path("Http/Controllers/Admin/WordFilter/V1/routes.php");
include app_path("Http/Controllers/Admin/Contract/V1/routes.php");
include app_path("Http/Controllers/Admin/Invoice/V1/routes.php");
include app_path("Http/Controllers/Admin/Payment/V1/routes.php");
include app_path("Http/Controllers/Admin/Media/V1/routes.php");
include app_path("Http/Controllers/Admin/Team/V1/routes.php");
include app_path("Http/Controllers/Admin/MallCoo/V1/routes.php");
include app_path("Http/Controllers/Admin/Privilege/V1/routes.php");
include app_path("Http/Controllers/Admin/Report/V1/routes.php");
include app_path("Http/Controllers/Admin/Warehouse/V1/routes.php");
include app_path("Http/Controllers/Admin/Activity/V1/routes.php");
include app_path('Http/Controllers/Admin/Common/V2/routes.php');
include app_path('Http/Controllers/Admin/Common/V3/routes.php');
include app_path('Http/Controllers/Admin/Demand/V1/routes.php');
include app_path('Http/Controllers/Admin/ResourceAuth/V1/routes.php');
include app_path('Http/Controllers/Admin/Product/V1/routes.php');
