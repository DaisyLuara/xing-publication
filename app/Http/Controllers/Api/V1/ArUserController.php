<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Transformers\ArUserTransformer;
use App\Models\ArUser;

class ArUserController extends Controller
{
    public function index(Request $request, ArUser $arUser)
    {
        $query = $arUser->query();
        $arUsers = $query->paginate(20);
        return $this->response->paginator($arUsers, new ArUserTransformer());
    }
}
