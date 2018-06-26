<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Api;

use App\Http\Controllers\Admin\ShortUrl\V1\Request\ShortUrlRequest;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Http\Request;
use App\Jobs\ShortUrlJob;
use Hashids\Hashids;

class ShortUrlController extends Controller
{
    protected $applications = [
        'AlipayClient' => 'alipay_client',
        'AliApp(TB' => 'aliapp_taobao',
        'MicroMessenger' => 'weixin',
    ];

    public function index(Request $request, ShortUrl $push)
    {
    }

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
            }
        }

        ShortUrlJob::dispatch($shortUrl->id, [
            'ua' => $request->userAgent(),
            'ip' => $request->getClientIp(),
            'browser' => Agent::browser(),
            'device' => Agent::device(),
            'platform' => Agent::platform(),
            'app' => $application,
        ])->onQueue('short_url');

        return redirect($shortUrl['target_url']);
    }

}
