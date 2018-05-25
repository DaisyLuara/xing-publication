<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\ArUserTransformer;
use App\Models\ArUser;

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
