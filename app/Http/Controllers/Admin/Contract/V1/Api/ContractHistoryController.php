<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午11:31
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractHistoryController extends Controller
{
    public function index(Request $request, Contract $contract)
    {

        $query = $contract->query();
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '{$request->get('start_date')}' and '{$request->get('end_date')}' ");
        }

        if ($request->filled('name')) {
            $name = $request->get('name');
            $query->whereHas('company', static function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($request->filled('owner')) {
            $query->where('owner', $request->get('owner'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('product_status')) {
            $query->where('product_status', $request->get('product_status'));
        }

        if ($request->filled('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();

        $query->whereHas('contractHistory', static function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $contracts = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response()->paginator($contracts, new ContractTransformer())->setStatusCode(200);
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'contract_history');
    }

}