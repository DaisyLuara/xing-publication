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
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function show(Permission $permission)
    {
        return $this->response()->item($permission, new PermissionTransformer())->setStatusCode(200);
    }

    public function index()
    {
        $permission = Permission::query()->where('parent_id', 0)->where('guard_name', 'web')->paginate(10);
        return $this->response()->paginator($permission, new PermissionTransformer())->setStatusCode(200);
    }

    public function store(PermissionRequest $request)
    {
        $permission = Permission::create($request->all());

        activity('create_permission')
            ->causedBy($this->user())
            ->performedOn($permission)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('新增权限');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->checkPermission($permission);

        if ($request->has('parent_id')) {
            abort(403, '不可移动节点');
        }
        $permission->update($request->all());

        activity('update_permission')
            ->causedBy($this->user())
            ->performedOn($permission)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('编辑权限');

        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Permission $permission, Request $request)
    {
        $this->checkPermission($permission);

        $permission->descendantsAndSelf()->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        activity('delete_permission')
            ->causedBy($this->user())
            ->performedOn($permission)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $request->all()])
            ->log('删除权限');

        return $this->response()->noContent()->setStatusCode(204);
    }


    private function checkPermission(Permission $permission): void
    {
        $perms = explode('.', $permission->name);
        if ($perms[0] === 'system') {
            abort(403, '系统基本权限不可更改');
        }
    }

}