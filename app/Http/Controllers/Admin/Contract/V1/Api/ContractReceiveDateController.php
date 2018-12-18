<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午11:41
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractReceiveDateController extends Controller
{

    public function index(Request $request, Contract $contract)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $currentDate = Carbon::now()->toDateString();
        $query = $contract->query();
        if ($request->name) {
            $query->where('name', $request->name);
        }
        if ($request->has('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->contract_number . '%');
        }

        if ($user->hasRole('user')) {
            $query->where('applicant', $user->id);
        }

        if ($user->hasRole('bd-manager')) {
            $query->whereHas('applicantUser', function ($q) use ($user) {
                $q->where('parent_id', $user->id);
            });
        }

        $contractReceiveDate = $query->paginate(10);
        return $this->response()->paginator($contractReceiveDate, new ContractTransformer());
    }
}