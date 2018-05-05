<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\RoleTransformer;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        return $this->response->collection($this->user()->getSystemRoles(), new RoleTransformer());
    }
}
