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
        $confession = $this->setImageUrl($request, $confession);

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
            $wxUserId = decrypt($request->get('sign'));
            $query->where('wx_user_id', $wxUserId);
        } else {
            $query->where('z', $request->get('z'));
        }

        $confession = $query->orderByDesc('id')->first();
        abort_if(!$confession, 204);

        $confession = $this->setImageUrl($request, $confession);

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

        $confession = $this->setImageUrl($request, $confession);

        return $this->response()->item($confession, new ConfessionTransformer());

    }

    /**
     * 照片url
     * @param Request $request
     * @param Confession $confession
     * @return mixed
     */
    private function setImageUrl($request, $confession)
    {
        if ($request->has('qiniu_id')) {
            /** @var FileUpload $fileUpload */
            $fileUpload = FileUpload::query()->find($request->get('qiniu_id'));
        }

        $confession->url = isset($fileUpload) ? $fileUpload->image : $confession->media->url;

        return $confession;
    }

}
