<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/18
 * Time: 下午5:19
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Controller;

class QiniuController extends Controller
{
    public function oauth()
    {
        $disk = \Storage::disk('qiniu');
        $token = $disk->getDriver()->uploadToken();
        return $token;
    }
}