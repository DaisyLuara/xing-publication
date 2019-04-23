<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/15
 * Time: 下午3:31
 */

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor;
use App\Http\Controllers\Admin\Common\V1\Request\WebsiteRequest;
use App\Http\Controllers\Admin\Face\V1\Models\XsFaceCountLog;
use App\Http\Controllers\Controller;
use App\Jobs\WebsiteMailJob;

class WebsiteController extends Controller
{

    protected $mailMapping = [
        '1' => 'bd@xingshidu.com',
        '2' => 'bd@xingshidu.com',
        '3' => 'bd@xingshidu.com',
    ];


    public function getFCpe()
    {
        $data = XsFaceCountLog::query()
            ->whereRaw("belong='all'")
            ->selectRaw('sum(playtimes7) as fcpe')
            ->first();
        $output = ['fcpe' => $data->fcpe];
        return response()->json($output);
    }

    public function storeVisitor(WebsiteRequest $request)
    {
        $contact = $request->get('contact');
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $contact;
        }

        if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            if (!preg_match('/^1[3456789]\d{9}$/', $contact)) {
                abort(422, '手机格式错误');
            } else {
                $data['phone'] = $contact;
            }
        }
        $data['name'] = $request->get('name');
        $data['remark'] = $request->get('remark');
        $data['subscribe'] = $request->get('subscribe');
        $data['type'] = $request->get('type');
        $visitor = WebsiteVisitor::create($data);

        if (env('APP_ENV') === 'production') {
            WebsiteMailJob::dispatch($this->mailMapping[$request->get('type')], $visitor)->onQueue('data-clean');
        } else {
            WebsiteMailJob::dispatch('yangqiang@jingfree.com', $visitor)->onQueue('data-clean');
        }
        return $this->response()->noContent()->setStatusCode(201);
    }
}