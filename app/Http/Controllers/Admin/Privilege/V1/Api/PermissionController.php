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
        $permission = Permission::query()->where('parent_id', $request->parent_id)->paginate(10);
        return $this->response()->paginator($permission, new PermissionTransformer());
    }

    public function store(PermissionRequest $request)
    {
        $this->checkUser();

        Permission::create($request->all());
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->checkUser();

        $this->checkPermission($permission);

        $permission->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Permission $permission)
    {
        $this->checkUser();

        $this->checkPermission($permission);

        $permission->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }

    private function checkUser()
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403, '无操作权限');
        }
    }

    private function checkPermission(Permission $permission)
    {
        if ($permission->name == "system") {
            abort(403, "系统基本权限不可更改");
        }
    }

}