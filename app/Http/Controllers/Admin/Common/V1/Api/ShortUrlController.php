<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;


use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use App\Http\Controllers\Admin\Common\V1\Request\ShortUrlRequest;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\build_query;
use function GuzzleHttp\Psr7\parse_query;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Http\Request;
use App\Jobs\ShortUrlJob;
use Hashids\Hashids;
use Log;

class ShortUrlController extends Controller
{
    protected $applications = [
        'AlipayClient' => 'alipay_client',
        'AliApp' => 'aliapp_taobao',
        'MicroMessenger' => 'weixin',
    ];

    public function store(ShortUrlRequest $request)
    {
        $shortUrl = ShortUrl::create(['target_url' => $request->get('url')]);
        $hashIds = new Hashids();
        $uri = $hashIds->encode($shortUrl->id);

        return response()->json(['short_url' => env('APP_URL') . "/api/s/" . $uri]);
    }

    public function redirect(string $short_path, Request $request)
    {
        $hashIds = new Hashids();
        $idArr = $hashIds->decode($short_path);
        if (empty($idArr)) {
            ding()->with('other')->text('短链接跳转失败:' . $request->getUri());
            abort(404, '您访问的页面不存在!');
        }

        $shortUrl = ShortUrl::findOrFail($idArr[0]);
        $application = '';
        foreach ($this->applications as $key => $value) {
            if (Agent::match($key)) {
                $application = $value;
                break;
            }
        }

        Log::info('short_url_app', [$application]);

        ShortUrlJob::dispatch($shortUrl->id, array_merge([
            'ua' => $request->userAgent(),
            'ip' => $request->getClientIp(),
            'browser' => Agent::browser(),
            'device' => Agent::device(),
            'platform' => Agent::platform(),
            'app' => $application,
        ], $request->all()))->onQueue('short_url');

        $queryString = $request->getQueryString();

        /**
         * 短链接跳转添加 随机优惠券
         * @todo 去掉
         */
        if ($shortUrl->id == 180) {
            $queryArr = parse_query($queryString);
            $queryArr['coupon_batch_id'] = array_random([111, 112, 113, 114]);
            $queryString = build_query($queryArr);
        }


        //大屏跳转参数加密
        $cookieExpire = time() + 3600 * 24 * 7;
        setcookie('game_attribute_payload', encrypt($queryString), $cookieExpire, '/', config('app')['cookie_domain']);

        $url = $shortUrl['target_url'];

        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if (strpos($url, '?') === false) {
            $url = $url . '?' . $queryString;
        } else {
            $url = $url . '&' . $queryString;
        }

        return redirect($url);
    }

}
