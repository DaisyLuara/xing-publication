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
use App\Mail\WebVisitor;

class WebsiteController extends Controller
{

    protected $mailMapping = [
//        '1' => 'bd@xingshidu.com',
//        '2' => 'ma@xingshidu.com',
//        '3' => 'bd@xingshidu.com',
        1 => '2121023687@qq.com',
        2 => '2121023687@qq.com',
        3 => '2121023687@qq.com'
    ];

    public function getFCpe()
    {
        $data = XsFaceCountLog::query()
            ->whereRaw("belong='all'")
            ->selectRaw("sum(playtimes7) as fcpe")
            ->first();
        $output = ['fcpe' => $data->fcpe];
        return response()->json($output);
    }

    public function storeVisitor(WebsiteRequest $request)
    {
        $contact = $request->contact;
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            $data['email'] = $contact;
        }

        if (!filter_var($contact, FILTER_VALIDATE_EMAIL) && !preg_match('/^1[3456789]\d{9}$/', $contact)) {
            abort(500, '手机格式错误');
        } else {
            $data['phone'] = $contact;
        }

        $data['name'] = $request->name;
        $data['remark'] = $request->remark;
        $data['subscribe'] = $request->subscribe;
        $data['type'] = $request->type;
        $visitor = WebsiteVisitor::create($data);

        \Mail::to($this->mailMapping[$request->type])->send(new WebVisitor($visitor));
        return $this->response()->noContent()->setStatusCode(201);
    }
}