<?php

namespace App\Http\Controllers\Admin\Contract\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractRequest;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Controller;
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
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%')
                    ->orWhere(function ($q) use ($name) {
                        $q->whereHas('company', function ($q) use ($name) {
                            $q->where('name', 'like', '%' . $name . '%');
                        });
                    });
            });
        }

        if ($request->status) {
            $query->where('status', '=', $request->status);
        }

        $contract = $query->orderBy('created_at', 'desc')->paginate(10);

        return $this->response->paginator($contract, new ContractTransformer());

    }

    public function store(ContractRequest $request, Contract $contract)
    {
        $role = Role::findByName('legal-affairs');
        $legal = $role->users()->first();
        $contract->fill(array_merge($request->all(), ['status' => 1, 'handler' => $legal->id]))->save();
        return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    public function update(ContractRequest $request, Contract $contract)
    {
        if ($contract->applicant == $contract->handler && $contract->status == 5) {
            $role = Role::findByName('legal-affairs');
            $legal = $role->users()->first();
            $contract->update(array_merge($request->all(), ['status' => 1, 'handler' => $legal->id]));
        } else {
            $contract->update(array_merge($request->all(), ['status' => 5, 'handler' => $contract->applicant]));
        }
        return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();
        return $this->response->noContent();
    }

    public function auditing(ContractRequest $request, Contract $contract)
    {
        /**@var $user \App\Models\User */
        $user = $this->user();
        $data = $user->getRoleNames();
        switch ($data[0]) {
            case 'legal-affairs':
                $contract->status = 2;
                $contract->handler = $user->parent_id;
                $contract->update();
                break;
            case 'legal-affairs-manager':
                $contract->handler = $contract->applicantUser->parent_id;
                $contract->update();
                break;
            case 'bd-manager':
                $contract->status = 3;
                $contract->handler = null;
                $contract->update();
                break;
            default:
                break;
        }

        $this->response->noContent();
    }

    public function specialAuditing(Contract $contract)
    {
        $role = Role::findByName('legal-affairs-manager');
        $legalManager = $role->users()->first();

        $contract->status = 4;
        $contract->handler = $legalManager->id;
        $contract->update();
        return $this->response->noContent();
    }
}
