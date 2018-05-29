<?php

namespace App\Http\Controllers\Admin\User\V1\Api;

use App\Http\Controllers\Admin\User\V1\Transformer\ArUserTransformer;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArUserController extends Controller
{
    public function index(Request $request, ArUser $arUser)
    {
        if ($this->user()->isUser()) {
            $query = $arUser->query();
            $arUser = $query->where('uid', '=', $this->user()->ar_user_id)->get();
            return $this->response->collection($arUser, new ArUserTransformer());
        } else {
            $query = $arUser->query();
            $arUsers = collect();
            if ($request->name) {
                $arUsers = $query->where('realname', 'like', '%' . $request->name . '%')->get();
            }
            return $this->response->collection($arUsers, new ArUserTransformer());
        }
    }
}
