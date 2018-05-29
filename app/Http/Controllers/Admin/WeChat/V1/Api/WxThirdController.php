<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Api;

use App\Http\Controllers\Admin\WeChat\V1\Transformer\WxThirdTransformer;
use App\Http\Controllers\Admin\WeChat\V1\Models\WxThird;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WxThirdController extends Controller
{
    public function index(Request $request, WxThird $wxThird)
    {
        $query = $wxThird->query();
        if ($request->type) {
            $type = $request->type;
            $query->whereHas('projectAdLaunch', function ($q) use ($type) {
                $q->where('type', '=', $type);
            });
        }
        $wxThird = $query->orderBy('date', 'desc')->paginate(10);
        return $this->response->paginator($wxThird, new WxThirdTransformer());
    }
}
