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
use Illuminate\Http\Request;

class ContractCostController extends Controller
{
    public function show(ContractCost $contractCost)
    {
        return $this->response()->item($contractCost, new ContractCostTransformer());
    }

    public function index(Request $request, ContractCost $contractCost)
    {
        $query = $contractCost->query();
        if ($request->start_date && $request->end_date) {
            $query->whereRaw("date_format(updated_at,'%Y-%m-%d') between '$request->start_date' and '$request->end_date' ");
        }

        $query->whereHas('contract', function ($q) use ($request) {
            if ($request->contract_number) {
                $q->where('contract_number', $request->contract_number);
            }

            if ($request->contract_name) {
                $q->where('name', 'liek', '%' . $request->contract_name . '%');
            }
        });

        $contractCost = $query->orderBy('updated_at', 'desc')->paginate(10);

        return $this->response()->paginator($contractCost, new ContractCostTransformer());
    }

    public function store(ContractCostRequest $request, ContractCost $contractCost)
    {
        $contractCost->fill($request->all())->save();

        $contents = $request->cost_content;
        foreach ($contents as $content) {
            ContractCostContent::create(array_merge($content, ['cost_id' => $contractCost->id, 'status' => 0, 'operator' => $content['creator']]));
        }

        return $this->response()->noContent()->setStatusCode(201);
    }
}