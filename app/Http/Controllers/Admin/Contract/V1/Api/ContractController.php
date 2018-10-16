<?php

namespace App\Http\Controllers\Admin\Contract\V1\Api;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Request\ContractRequest;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    public function show(Contract $contract)
    {
        return $this->response->item($contract, new ContractTransformer());
    }

    public function index(ContractRequest $request, Contract $contract)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = $contract->query();

        if ($request->name) {
            $name = $request->name;
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%')
                    ->orWhere(function ($q) use($name){
                        $q->whereHas('company', function ($q) use($name){
                            $q->where('name', 'like', '%' . $name . '%');
                        });
                    });
            });
        }

        if ($request->status) {
            $query->where('status', '=', $request->status);
        }

        $contract = $query->whereRaw("date_format(created_at,'%Y-%m-%d') between '$startDate' and '$endDate' ")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->response->paginator($contract, new ContractTransformer());

    }

    public function store(ContractRequest $request, Contract $contract)
    {
        $contract->fill($request->all())->save();
        return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    public function update(ContractRequest $request, Contract $contract)
    {
        $contract->update($request->all());
        return $this->response->item($contract, new ContractTransformer())->setStatusCode(201);
    }

    public function destroy(Contract $contract){
        $contract->delete();
        return $this->response->noContent();
    }
}
