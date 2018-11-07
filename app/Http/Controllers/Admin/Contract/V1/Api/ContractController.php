<?php

namespace App\Http\Controllers\Admin\Contract\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractRequest;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ContractController extends Controller
{
    public function show(Contract $contract)
    {
        return $this->response->item($contract, new ContractTransformer());
    }

    public function index(ContractRequest $request, Contract $contract)
    {

        $query = $contract->query();
        if ($request->start_date && $request->end_date) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ");
        }

        if ($request->name) {
            $name = $request->name;
            $query->whereHas('company', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }
//        if ($request->name) {
//            $name = $request->name;
//            $query->where(function ($query) use ($name) {
//                $query->where('name', 'like', '%' . $name . '%')
//                    ->orWhere(function ($q) use ($name) {
//                        $q->whereHas('company', function ($q) use ($name) {
//                            $q->where('name', 'like', '%' . $name . '%');
//                        });
//                    });
//            });
//        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->contract_number . '%');
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('operation')) {
            $query->where('status', '=', 3);
        } else {
            $query->whereRaw("(applicant = $user->id or handler = $user->id)");
        }
        $contract = $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->response->paginator($contract, new ContractTransformer());
    }

    public function remindIndex(Request $request, Contract $contract)
    {
        $user = $this->user();
        $currentDate = Carbon::now()->toDateString();
        $query = $contract->query();
        if ($request->name) {
            $query->where('name', '=', $request->name);
        }
        if ($request->has('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->contract_number . '%');
        }
        $contract = $query->whereHas('receiveDate', function ($q) use ($currentDate) {
            $q->whereRaw("'$currentDate' between date_add(date, interval - 5 day) and date_add(date, interval 3 day)");
        })
            ->whereRaw("(applicant = $user->id or handler = $user->id)")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->response->paginator($contract, new ContractTransformer());

    }

    public function store(ContractRequest $request, Contract $contract)
    {
        $user = $this->user();
        if (!$user->parent_id) {
            abort(500, '无所属主管，无法新增合同申请');
        }
        $role = Role::findByName('legal-affairs');
        $legals = $role->users()->get();
        foreach ($legals as $legal) {
            if ($legal->hasPermissionTo('auditing')) {
                $contract->fill(array_merge($request->all(), ['status' => 1, 'handler' => $legal->id]))->save();
                //文档存储
                $ids = explode(',', $request->ids);
                foreach ($ids as $id) {
                    Media::where('id', '=', $id)->update(['contract_id' => $contract->id]);
                }

                if ($request->type == 0) {
                    //收款日期存储
                    $dates = explode(',', $request->receive_date);
                    foreach ($dates as $date) {
                        ContractReceiveDate::create(['contract_id' => $contract->id, 'date' => $date]);
                    }
                }
                return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
            }
        }
        abort(500, "无审批的法务,请联系管理员");
    }

    public function update(ContractRequest $request, Contract $contract)
    {
        if ($contract->applicant == $contract->handler && $contract->status == 5) {
            $role = Role::findByName('legal-affairs');
            $legals = $role->users()->get();
            foreach ($legals as $legal) {
                if ($legal->hasPermissionTo('auditing')) {
                    $contract->update(array_merge($request->all(), ['status' => 1, 'handler' => $legal->id]));
                    //修改文件
                    $ids = $request->ids;
                    Media::query()->whereRaw(" contract_id = $contract->id and id not in($ids)")->delete();
                    Media::query()->whereRaw(" id in($ids)")->update(['contract_id' => $contract->id]);

                    //修改收款日期
                    $dates = explode(',', $request->receive_date);
                    ContractReceiveDate::query()->where('contract_id', $contract->id)->delete();
                    foreach ($dates as $date) {
                        ContractReceiveDate::create(['contract_id' => $contract->id, 'date' => $date]);
                    }
                    return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
                }
            }
            abort(500, "无审批的法务,请联系管理员");
        } else {
            /** @var  $user \App\Models\User */
            $user = $this->user();
            if ($user->hasRole('legal-affairs')) {
                $contract->update(array_merge($request->all(), ['status' => 5, 'handler' => $contract->applicant]));
                $ids = $request->ids;
                Media::query()->whereRaw(" contract_id = $contract->id and id not in($ids)")->delete();
                Media::query()->whereRaw(" id in($ids)")->update(['contract_id' => $contract->id]);
            } else {
                $contract->update(array_merge($request->all(), ['status' => 5, 'handler' => $contract->applicant]));
            }
            return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
        }
    }

    public function destroy(Contract $contract)
    {
        if ($contract->status != 1) {
            abort(500, "合同审批状态已更改，不可删除");
        }
//        ContractReceiveDate::query()->where('contract_id', $contract->id)->delete();
        $contract->delete();
        return $this->response->noContent();
    }

    public function auditing(Request $request, Contract $contract)
    {
        /**@var $user \App\Models\User */
        $user = $this->user();

        if ($user->hasRole('legal-affairs')) {
            $contract->status = 2;
            $contract->handler = $user->parent_id;
            $contract->update();
        } else if ($user->hasRole('legal-affairs-manager')) {
            $contract->status = 2;
            $contract->handler = $contract->applicantUser->parent_id;
            $contract->update();
        } else if ($user->hasRole('bd-manager')) {
            $contract->status = 3;
            $contract->handler = null;
            $contract->update();
        }

        return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    public function specialAuditing(Request $request, Contract $contract)
    {
        $role = Role::findByName('legal-affairs-manager');
        $legalManager = $role->users()->first();

        if ($contract->status == 2) {
            abort(500, "合同审核中无法申请特批");
        }
        $contract->status = 4;
        $contract->handler = $legalManager->id;
        $contract->update();
        return $this->response->noContent();
    }
}
