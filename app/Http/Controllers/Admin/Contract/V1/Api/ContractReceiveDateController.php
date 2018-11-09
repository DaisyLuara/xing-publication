<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/9
 * Time: 上午11:41
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractReceiveDateTransformer;

class ContractReceiveDateController extends Controller
{

    public function index(Request $request, ContractReceiveDate $contractReceiveDate)
    {
        $user = $this->user();
        $currentDate = Carbon::now()->toDateString();
        $query = $contractReceiveDate->query();
        if ($request->name) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('name', $request->name);
            });
        }
        if ($request->has('contract_number')) {
            $query->whereHas('contract', function ($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->contract_number . '%');
            });
        }

        $query->whereHas('contract', function ($q) use ($request, $user) {
            $q->whereRaw("(applicant = $user->id or handler = $user->id)");
        });

        $contractReceiveDate = $query
            ->whereRaw("'$currentDate' between date_add(receive_date, interval - 5 day) and date_add(receive_date, interval 3 day)")
            ->paginate(10);
        return $this->response()->paginator($contractReceiveDate, new ContractReceiveDateTransformer());
    }
}