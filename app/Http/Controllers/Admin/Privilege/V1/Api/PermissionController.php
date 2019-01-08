<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/12/21
 * Time: 下午4:54
 */

namespace App\Http\Controllers\Admin\Privilege\V1\Api;


use App\Http\Controllers\Admin\Privilege\V1\Request\PermissionRequest;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\PermissionTransformer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function show(Permission $permission)
    {
        return $this->response()->item($permission, new PermissionTransformer());
    }

    public function index(Request $request)
    {
        $permission = Permission::query()->where('parent_id', 0)->paginate(10);
        return $this->response()->paginator($permission, new PermissionTransformer());
    }

    public function store(PermissionRequest $request)
    {
        Permission::create($request->all());
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->checkPermission($permission);

        $permission->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Permission $permission)
    {
        $this->checkPermission($permission);

        $permission->descendantsAndSelf()->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }


    private function checkPermission(Permission $permission)
    {
        $perms = explode('.', $permission->name);
        if ($perms[0] == "system") {
            abort(403, "系统基本权限不可更改");
        }
    }

}