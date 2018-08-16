<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;


use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use App\Http\Controllers\Admin\Common\V1\Request\ShortUrlRequest;
use App\Http\Controllers\Controller;
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
        $shortUrl = ShortUrl::findOrFail($hashIds->decode($short_path)[0]);
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
        $url = $shortUrl['target_url'];

        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
        $url = substr($url, 0, -1);
        if (strpos($url, '?') === false) {
            $url = $url . '?' . $queryString;
        } else {
            $url = $url . '&' . $queryString;
        }

        return redirect($url);
    }

}
