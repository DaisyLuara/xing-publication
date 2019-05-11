<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\Common\V3\Transformer\ConfessionTransformer;
use App\Http\Controllers\Admin\Common\V3\Request\ConfessionRequest;
use App\Http\Controllers\Admin\Common\V3\Models\Confession;
use App\Http\Controllers\Admin\Common\V1\Models\FileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfessionsController extends Controller
{

    /**
     * 上传告白
     * @param ConfessionRequest $request
     * @param Confession $confession
     * @return \Dingo\Api\Http\Response
     */
    public function store(ConfessionRequest $request, Confession $confession)
    {
        $confession->fill($request->all());

        if ($request->has('sign')) {
            $confession->wx_user_id = decrypt($request->get('sign'));
        }

        $confession->save();

        return $this->response()->item($confession, new ConfessionTransformer())
            ->setStatusCode(201);
    }

    /**
     * 查询告白
     * @param ConfessionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function show(ConfessionRequest $request)
    {
        $query = Confession::query();

        if ($request->has('sign')) {
            if ($request->has('utm_source_id')) {
                $query->where('id', $request->get('utm_source_id'));
            } else {
                $query->where('wx_user_id', decrypt($request->get('sign')));
            }
        } else {
            $query->where('z', $request->get('z'));
        }

        if ($request->has('utm_campaign')) {
            $query->where('utm_campaign', $request->get('utm_campaign'));
        }

        $confession = $query->orderByDesc('id')->first();
        abort_if(!$confession, 204);

        return $this->response()->item($confession, new ConfessionTransformer());

    }

    /**
     * 提取告白
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function extract(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^1[3456789]\d{9}$/',
        ]);

        $phone = $request->get('phone');

        /** @var Confession $confession */
        $confession = Confession::query()->where('phone', $phone)->orderByDesc('id')->first();
        abort_if(!$confession, 204);

        return $this->response()->item($confession, new ConfessionTransformer());

    }

    /**
     * 更新告白页
     * @param ConfessionRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(ConfessionRequest $request)
    {
        $wxUserId = decrypt($request->get('sign'));

        $confession = Confession::query()->where('wx_user_id', $wxUserId)
            ->where('utm_campaign', $request->get('utm_campaign'))
            ->first();

        if ($request->has('utm_source_id') && !$confession) {
            $confession = Confession::query()->create(array_merge(['wx_user_id' =>$wxUserId], $request->all()));
        } else {
            $confession->update($request->only(['media_id', 'record_id', 'message']));
        }

        return $this->response()->item($confession, new ConfessionTransformer());
    }

}
