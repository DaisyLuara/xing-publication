<?php

namespace App\Http\Controllers\Admin\Common\V3\Api;

use App\Http\Controllers\Admin\Common\V3\Transformer\ConfessionTransformer;
use App\Http\Controllers\Admin\Common\V3\Request\ConfessionRequest;
use App\Http\Controllers\Admin\Common\V3\Models\Confession;
use App\Http\Controllers\Controller;

class ConfessionsController extends Controller
{

    public function store(ConfessionRequest $request, Confession $confession)
    {
        $confession->fill($request->all());
        $confession->wx_user_id = decrypt($request->get('sign'));
        $confession->save();

        return $this->response()->item($confession, new ConfessionTransformer())
            ->setStatusCode(201);
    }

    public function show(ConfessionRequest $request)
    {
        $query = Confession::query();
        $wxUserId = decrypt($request->get('sign'));

        if ($request->has('phone')) {
            $query->where('phone', $request->get('phone'));
        } else {
            $query->where('wx_user_id', $wxUserId);
        }

        $confession = $query->orderByDesc('id')->first();
        abort_if(!$confession, 204);

        return $this->response()->item($confession, new ConfessionTransformer());

    }

}
