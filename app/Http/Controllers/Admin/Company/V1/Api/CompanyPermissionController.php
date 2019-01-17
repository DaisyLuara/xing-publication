<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/9
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Admin\Company\V1\Api;


use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Request\PermissionRequest;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\PermissionTransformer;
use App\Http\Controllers\Controller;

class CompanyPermissionController extends Controller
{
    public function show(Permission $permission)
    {
        return $this->response()->item($permission, new PermissionTransformer());
    }

    public function index()
    {
        $permission = Permission::query()->where('parent_id', 0)
            ->where('guard_name', 'shop')
            ->paginate(10);
        return $this->response()->paginator($permission, new PermissionTransformer());
    }

    public function store(PermissionRequest $request)
    {
        Permission::create(array_merge($request->all(), ['guard_name' => 'shop']));
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Permission $permission)
    {
        $permission->descendantsAndSelf()->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }
}