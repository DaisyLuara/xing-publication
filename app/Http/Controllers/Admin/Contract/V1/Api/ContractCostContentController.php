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
use App\Models\User;
use Illuminate\Http\Request;

class ContractCostContentController extends Controller
{
    public function store(ContractCostContentRequest $request, ContractCost $contractCost, ContractCostContent $costContent)
    {
        $status = 0;
        $confirm_cost = $contractCost->confirm_cost;
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('legal-affairs-manager')) {
            $status = 1;
            $confirm_cost = $confirm_cost + $request->money;
        }
        $costContent->fill(array_merge($request->all(), ['cost_id' => $contractCost->id, 'status' => $status, 'operator' => $request->creator]))->save();
        $contractCost->update(['total_cost' => $request->total_cost, 'confirm_cost' => $confirm_cost]);
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(ContractCostContentRequest $request, ContractCost $contractCost, ContractCostContent $content)
    {
        if ($content->status == 1) {
            abort(403, '成本明细已确认,无法修改');
        }
        $user = $this->user();
        abort_if(!$this->check($user, $content), '403', '无操作权限');
        $content->update(array_merge($request->all(), ['operator' => $user->name]));
        $contractCost->update(['total_cost' => $request->total_cost]);
        return $this->response()->noContent();
    }

    private function check(User $user, ContractCostContent $content)
    {
        if ($user->hasRole('legal-affairs-manager')) {
            return true;
        }
        if ($user->id == $content->creator_id) {
            return true;
        }
        return false;
    }

    public function destroy(Request $request, ContractCost $contractCost, ContractCostContent $content)
    {
        if ($content->status == 1) {
            abort(403, '成本明细已确认,无法删除');
        }
        $user = $this->user();
        abort_if($user->id != $content->creator_id, '403', '无操作权限');
        $content->delete();
        $contractCost->update(['total_cost' => $contractCost->total_cost - $content->money]);
        return $this->response()->noContent()->setStatusCode(204);
    }

    public function confirm(ContractCostContent $content)
    {
        $content->update(['status' => 1]);
        ContractCost::query()->where('id', $content->cost_id)->increment('confirm_cost', $content->money);
        return $this->response()->noContent();
    }
}