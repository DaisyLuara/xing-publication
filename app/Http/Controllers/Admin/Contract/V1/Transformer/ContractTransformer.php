<?php

namespace App\Http\Controllers\Admin\Contract\V1\Transformer;

use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use League\Fractal\TransformerAbstract;

class ContractTransformer extends TransformerAbstract
{
    public function transform(Contract $contract){
        return [
            'id'=>$contract->id,
            'contract_number'=>$contract->contract_number,
            'company_name'=>$contract->company->name,
            'name'=>$contract->name,
            'applicant'=>$contract->user->name,
            'status'=>$contract->status,
            'handle'=>$contract->handle,
            'type'=>$contract->type,
            'receive_date'=>$contract->receive_date,
            'content'=>$contract->content,
            'remark'=>$contract->remark,
            'created_at'=>$contract->created_at,
            'update_at'=>$contract->update_at,
        ];
    }
}