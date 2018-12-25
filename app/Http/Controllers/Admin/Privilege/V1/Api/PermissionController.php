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

class PermissionController extends Controller
{
    public function show(Permission $permission)
    {
        return $this->response()->item($permission, new PermissionTransformer());
    }

    public function index()
    {
        $permission = Permission::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->toHierarchy();
        return response()->json($permission);
    }

    public function store(PermissionRequest $request)
    {
        $this->checkPermission();

        Permission::create($request->all());
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->checkPermission();

        $permission->update($request->all());
        return $this->response()->noContent()->setStatusCode(200);
    }

    public function destroy(Permission $permission)
    {
        $this->checkPermission();

        $permission->delete();
        return $this->response()->noContent()->setStatusCode(204);
    }

    private function checkPermission()
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403, '无操作权限');
        }
    }

}