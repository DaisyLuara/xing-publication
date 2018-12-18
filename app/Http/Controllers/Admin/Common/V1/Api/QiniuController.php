<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/18
 * Time: 下午5:19
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Controller;
use Qiniu\Auth;

class QiniuController extends Controller
{
    public function oauth()
    {
        $accessKey = 'QwzBG0rrX-sFVvFft2A2vhqNVAnM1Nrg8PGf3VX4';
        $secretKey = '95j_UlL2_PrSYw2q-5Z2R8e29B3V39PXIFZ5IEcf';
        $bucket = 'publication';

        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);

        return response()->json($token);
    }
}