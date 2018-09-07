<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\PointContract;
use League\Fractal\TransformerAbstract;

class PointContractTransformer extends TransformerAbstract
{
    public function transform(PointContract $pointContract)
    {
        return [
            'oid' => $pointContract->oid,
            'type' => $pointContract->type,
            'contract' => $pointContract->contract,
            'contract_company' => $pointContract->contract_company,
            'contract_num' => $pointContract->contract_num,
            'contract_user' => $pointContract->contract_user,
            'contract_phone' => $pointContract->contract_phone,
            'pay' => $pointContract->pay,
            'enter_sdate' => date('Y-m-d H:i:s', $pointContract->enter_sdate),
            'enter_edate' => date('Y-m-d H:i:s', $pointContract->enter_edate),
            'oper_sdate' => date('Y-m-d H:i:s', $pointContract->oper_sdate),
            'oper_edate' => date('Y-m-d H:i:s', $pointContract->oper_edate),
            'mode' => $pointContract->mode,
            'ad_istar' => $pointContract->ad_istar,
            'ad_ads' => $pointContract->ad_ads,
            'exchange_num' => $pointContract->exchange_num,
            'date' => $pointContract->date,
        ];
    }

}