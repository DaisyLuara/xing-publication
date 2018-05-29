<?php

namespace App\Http\Controllers\Admin\User\V1\Api;

use App\Http\Controllers\Admin\User\V1\Transformer\PermissionTransformer;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = $this->user()->getAllPermissions();

        return $this->response->collection($permissions, new PermissionTransformer());
    }
}
