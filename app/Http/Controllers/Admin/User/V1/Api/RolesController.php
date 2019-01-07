<?php

namespace App\Http\Controllers\Admin\User\V1\Api;

use App\Http\Controllers\Admin\User\V1\Transformer\RoleTransformer;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index()
    {
        return $this->response->collection($this->user()->getSystemRoles(), new RoleTransformer());
    }
}
