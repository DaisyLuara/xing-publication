<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\ArUserTransformer;
use App\Models\ArUser;

class ArUserController extends Controller
{
    public function index(Request $request, ArUser $arUser)
    {
        if($this->user()->isAdmin()){
            $query = $arUser->query();
            $arUsers = $query->paginate(20);
            return $this->response->paginator($arUsers, new ArUserTransformer());
        }else{
            $query=$arUser->query();
            $arUser=$query->where('uid','=',$this->user()->ar_user_id)->get();
            return $this->response->item($arUser,new ArUserTransformer());
        }
    }
}
