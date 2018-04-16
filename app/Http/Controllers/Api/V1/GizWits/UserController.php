<?php

namespace App\Http\Controllers\Api\V1\GizWits;

use App\Http\Controllers\Api\V1\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $giz_wits = app('giz_wits');
        return response()->json($giz_wits->users());
    }
}
