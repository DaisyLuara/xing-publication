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
use Dingo\Api\Http\Response;
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
            $confirm_cost += $request->get('money');
        }
        $costContent->fill(array_merge($request->all(), ['cost_id' => $contractCost->id, 'status' => $status]))->save();
        $contractCost->update(['total_cost' => $request->get('total_cost'), 'confirm_cost' => $confirm_cost]);
        return $this->response()->noContent()->setStatusCode(201);
    }

    public function update(ContractCostContentRequest $request, ContractCost $contractCost, ContractCostContent $content): Response
    {
        if ($content->status === 1) {
            abort(403, '成本明细已确认,无法修改');
        }
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if (!($user->id === $content->creator_id || $user->hasRole('legal-affairs-manager'))) {
            abort_if('403', '无操作权限');
        }

        $status = 0;
        $confirm_cost = $contractCost->confirm_cost;
//        if ($user->hasRole('legal-affairs-manager')) {
//            $status = 1;
//            $confirm_cost += $request->get('money');
//        }
        $content->update(array_merge($request->all(), ['status' => $status]));
        $contractCost->update(['total_cost' => $request->get('total_cost'), 'confirm_cost' => $confirm_cost]);
        return $this->response()->noContent();
    }

    public function destroy(ContractCost $contractCost, ContractCostContent $content)
    {
        if ($content->status === 1) {
            abort(403, '成本明细已确认,无法删除');
        }
        $user = $this->user();
        abort_if($user->id !== $content->creator_id, '403', '无操作权限');
        $content->delete();
        $contractCost->update(['total_cost' => $contractCost->total_cost - $content->money]);
        return $this->response()->noContent()->setStatusCode(204);
    }

    public function confirm(ContractCostContent $content): Response
    {
        $content->update(['status' => 1]);
        ContractCost::query()->where('id', $content->cost_id)->increment('confirm_cost', $content->money);
        return $this->response()->noContent();
    }
}