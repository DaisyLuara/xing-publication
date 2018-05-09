<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\WxThird;
use App\Transformers\WxThirdTransformer;
use function foo\func;
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
