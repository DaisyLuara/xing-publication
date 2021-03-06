<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/1/29
 * Time: 上午10:54
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractCost;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostContent;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractCostRequest;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractCostTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class ContractCostController extends Controller
{
    public function show(ContractCost $contractCost): Response
    {
        return $this->response()->item($contractCost, new ContractCostTransformer());
    }

    public function index(Request $request, ContractCost $contractCost): Response
    {
        $query = $contractCost->query();
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereRaw("date_format(updated_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        $user = $this->user();
        $query->whereHas('contract', static function ($q) use ($request, $user) {
            if ($request->filled('contract_number')) {
                $q->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
            }

            if ($request->filled('contract_name')) {
                $q->where('name', 'like', '%' . $request->get('contract_name') . '%');
            }

            if ($user->hasRole('user|bd-manager')) {
                $q->where('owner', $user->id);
            }
        });

        $contractCosts = $query->orderBy('updated_at', 'desc')->paginate(10);

        return $this->response()->paginator($contractCosts, new ContractCostTransformer());
    }

    public function store(ContractCostRequest $request, ContractCost $contractCost)
    {
        $params = $request->all();
        $status = 0;
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('legal-affairs-manager')) {
            $status = 1;
            $contractCost->fill(array_merge($params, ['confirm_cost' => $request->get('total_cost')]))->save();
        } else {
            $contractCost->fill($params)->save();
        }
        $contents = $request->get('cost_content');
        foreach ($contents as $content) {
            ContractCostContent::query()->create(array_merge($content, ['cost_id' => $contractCost->id, 'status' => $status]));
        }

        activity('create_contract_cost')
            ->causedBy($this->user())
            ->performedOn($contractCost)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $params])
            ->log('新增合同成本');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'contract_cost');
    }
}