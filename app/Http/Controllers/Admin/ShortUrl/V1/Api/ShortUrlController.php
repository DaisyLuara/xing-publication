<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Api;

use App\Http\Controllers\Admin\ShortUrl\V1\Request\ShortUrlRequest;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use App\Http\Controllers\Admin\ShortUrl\V1\Transformer\ShortUrlTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    protected $applications = [
        'AlipayClient' => 'alipay_client',
        'AliApp' => 'aliapp_taobao',
        'MicroMessenger' => 'weixin',
    ];

    public function index(Request $request, ShortUrl $shortUrl)
    {
        $query = $shortUrl->query();
        if ($request->description) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        $shortUrls = $query->paginate(10);
        return $this->response->paginator($shortUrls, new ShortUrlTransformer());
    }

    public function store(ShortUrlRequest $request, ShortUrl $shortUrl)
    {
        $shortUrl->fill($request->all())->save();
        return $this->response->item($shortUrl, new ShortUrlTransformer());
    }

}
