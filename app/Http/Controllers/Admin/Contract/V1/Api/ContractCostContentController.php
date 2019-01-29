<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 下午3:13
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractCost;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractCostContentRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractCostContentController extends Controller
{
    public function store(ContractCostContentRequest $request, ContractCost $contractCost, ContractCostContent $costContent)
    {
        $costContent->fill(array_merge($request->all(), ['cost_id' => $contractCost->id, 'status' => 0, 'operator' => $request->creator]))->save();
        $contractCost->update(['total_cost' => $request->total_cost]);
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(ContractCostContentRequest $request, ContractCost $contractCost, ContractCostContent $content)
    {
        if ($content->status == 1) {
            abort(403, '成本明细已确认,无法修改');
        }
        $user = $this->user();
        $content->update(array_merge($request->all(), ['operator' => $user->name]));
        $contractCost->update(['total_cost' => $request->total_cost]);
        return $this->response()->noContent();
    }

    public function destroy(Request $request, ContractCost $contractCost, ContractCostContent $content)
    {
        if ($content->status == 1) {
            abort(403, '成本明细已确认,无法删除');
        }
        $content->delete();
        $contractCost->update(['total_cost' => $request->total_cost]);
        return $this->response()->noContent()->setStatusCode(204);
    }

    public function confirm(ContractCostContent $content)
    {
        $content->update(['status' => 1]);
        return $this->response()->noContent();
    }
}