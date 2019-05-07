<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/7
 * Time: 下午3:48
 */

namespace App\Http\Controllers\Admin\Resource\V1\Api;


use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMediaGroup;
use App\Http\Controllers\Admin\Resource\V1\Request\PublicationMediaGroupRequest;
use App\Http\Controllers\Admin\Resource\V1\Request\PublicationMediaRequest;
use App\Http\Controllers\Admin\Resource\V1\Transformer\PublicationMediaGroupTransformer;
use App\Http\Controllers\Controller;

class PublicationMediaGroupController extends Controller
{
    public function index(PublicationMediaGroup $group)
    {
        $query = $group->query();
        $group = $query->orderBy('id')->get();
        return $this->response()->collection($group, new PublicationMediaGroupTransformer())->setStatusCode(200);
    }

    public function store(PublicationMediaGroupRequest $request, PublicationMediaGroup $group)
    {
        $group->fill($request->all())->save();
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PublicationMediaGroupRequest $request, PublicationMediaGroup $group)
    {
        if ($group->id === 1) {
            abort(403, '默认分组不可更改');
        }
        $group->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }
}