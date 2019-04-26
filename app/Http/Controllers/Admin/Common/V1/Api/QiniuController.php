<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/18
 * Time: 下午5:19
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Image;

class QiniuController extends Controller
{
    protected $allowed_ext = ['doc', 'docx', 'pdf', 'png', 'jpg', 'jpeg'];

    public function oauth()
    {
        $disk = \Storage::disk('qiniu');
        $token = $disk->getDriver()->uploadToken();
        return $token;
    }

    public function sign()
    {
        $method = 'POST';
        $Path = '/v3/image/censor';
        $host = 'ai.qiniuapi.com';
        $data = "<$method> <$Path>\nHost: <$host>\n\n";

        $auth = new Auth(config('filesystems.disks.qiniu_yq.access_key'), config('filesystems.disks.qiniu_yq.access_key'));
        return $auth->sign($data);

    }

    public function callback(Request $request)
    {
        Log::info('test');
        Log::info($request->all());
    }
}