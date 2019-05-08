<?php

namespace App\Http\Controllers\Admin\Credit\V1\Api;

use App\Http\Controllers\Admin\Credit\V1\Models\Credit;
use App\Http\Controllers\Admin\Credit\V1\Transformer\CreditTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class CreditController extends Controller
{

    /**
     * 场地主分值列表
     * @param Request $request
     * @param Credit $credit
     * @return Response
     */
    public function index(Request $request, Credit $credit): Response
    {

        $query = $credit->query()->where('role_id', '=', 11);

        if ($request->get('mobile')) {
            $query->where('mobile', 'like', '%' . $request->get('mobile') . '%');
        }

        if ($request->get('p_groupid')) {
            $query->where('p_groupid', '=', $request->get('p_groupid'));
        }

        $credits = $query->orderByDesc('p_credits')->paginate(10);
        return $this->response->paginator($credits, new CreditTransformer());
    }

}
