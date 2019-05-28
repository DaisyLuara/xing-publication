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
        if ($request->get('start_date') && $request->get('end_date')) {
            $query->whereRaw("date_format(updated_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        $query->whereHas('contract', static function ($q) use ($request) {
            if ($request->has('contract_number')) {
                $q->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
            }

            if ($request->get('contract_name')) {
                $q->where('name', 'like', '%' . $request->get('contract_name') . '%');
            }
        });
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user|bd-manager')) {
            $query->where('applicant_id', $user->id);
        }
        $contractCosts = $query->orderBy('updated_at', 'desc')->paginate(10);

        return $this->response()->paginator($contractCosts, new ContractCostTransformer());
    }

    public function store(ContractCostRequest $request, ContractCost $contractCost)
    {
        $status = 0;
        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('legal-affairs-manager')) {
            $status = 1;
            $contractCost->fill(array_merge($request->all(), ['confirm_cost' => $request->get('total_cost')]))->save();
        } else {
            $contractCost->fill($request->all())->save();
        }
        $contents = $request->get('cost_content');
        foreach ($contents as $content) {
            ContractCostContent::create(array_merge($content, ['cost_id' => $contractCost->id, 'status' => $status]));
        }

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'contract_cost');
    }

}