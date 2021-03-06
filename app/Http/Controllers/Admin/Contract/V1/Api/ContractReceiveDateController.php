<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午11:41
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractReceiveDateRequest;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class ContractReceiveDateController extends Controller
{

    public function index(Request $request, Contract $contract): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();

        $query = $contract->query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->filled('owner')) {
            $query->where('owner', '=', $request->get('owner'));
        }

        if ($request->filled('company_name')) {
            $query->whereHas('company', static function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->get('company_name') . '%');
            });
        }
        if ($request->filled('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
        }

        if ($user->hasRole('user')) {
            $query->where('owner', $user->id);
        }

        if ($user->hasRole('bd-manager')) {
            $query->whereHas('ownerUser', static function ($q) use ($user) {
                $q->where('parent_id', $user->id);
            });
        }

        $contractReceiveDate = $query->where('status', 3)
            ->orderByDesc('created_at')->paginate(10);
        return $this->response()->paginator($contractReceiveDate, new ContractTransformer());
    }

    public function store(ContractReceiveDateRequest $request, Contract $contract, ContractReceiveDate $contractReceiveDate)
    {
        $params = $request->all();
        $contractReceiveDate->fill(array_merge($params, ['contract_id' => $contract->id, 'receive_status' => 0]))->save();

        activity('create_contract_receive_date')
            ->causedBy($this->user())
            ->performedOn($contractReceiveDate)
            ->withProperties(['ip' => $request->getClientIp(), 'request_params' => $params])
            ->log('新增合同收款日期');

        return $this->response()->noContent()->setStatusCode(201);
    }

    public function export(Request $request)
    {
        return excelExportByType($request, 'remind_contract');
    }

}