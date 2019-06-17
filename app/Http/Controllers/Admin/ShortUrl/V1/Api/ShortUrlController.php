<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Request\ExportRequest;
use App\Http\Controllers\Admin\ShortUrl\V1\Request\ShortUrlRequest;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use App\Http\Controllers\Admin\ShortUrl\V1\Transformer\ShortUrlTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    protected $applications = [
        'AlipayClient' => 'alipay_client',
        'AliApp' => 'aliapp_taobao',
        'MicroMessenger' => 'weixin',
    ];

    public function index(Request $request, ShortUrl $shortUrl): Response
    {
        $query = $shortUrl->query();
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->get('description') . '%');
        }
        if ($request->filled('url_type')) {
            $query->where('url_type', $request->get('url_type'));
        }
        $shortUrls = $query->orderByDesc('id')->paginate(10);
        return $this->response()->paginator($shortUrls, new ShortUrlTransformer());
    }

    public function store(ShortUrlRequest $request, ShortUrl $shortUrl): Response
    {
        $shortUrl->fill($request->all())->save();

        activity('create_short_url')
            ->causedBy($this->user())
            ->performedOn($shortUrl)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增短链接');

        return $this->response()->item($shortUrl, new ShortUrlTransformer());
    }

    public function export(ExportRequest $request)
    {
        return excelExport($request);
    }
}
