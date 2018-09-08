<?php

namespace App\Http\Controllers\Admin\Point\V1\Transformer;

use App\Http\Controllers\Admin\Point\V1\Models\MarketContract;
use League\Fractal\TransformerAbstract;

class MarketContractTransformer extends TransformerAbstract
{
    public function transform(MarketContract $marketContract)
    {
        return [
            'marketid' => $marketContract->marketid,
            'type' => $marketContract->type,
            'contract' => $marketContract->contract,
            'contract_company' => $marketContract->contract_company,
            'contract_num' => $marketContract->contract_num,
            'contract_user' => $marketContract->contract_user,
            'contract_phone' => $marketContract->contract_phone,
            'pay' => $marketContract->pay,
            'enter_sdate' => date('Y-m-d H:i:s', $marketContract->enter_sdate),
            'enter_edate' => date('Y-m-d H:i:s', $marketContract->enter_edate),
            'oper_sdate' => date('Y-m-d H:i:s', $marketContract->oper_sdate),
            'oper_edate' => date('Y-m-d H:i:s', $marketContract->oper_edate),
            'mode' => $marketContract->mode,
            'ad_istar' => $marketContract->ad_istar,
            'ad_ads' => $marketContract->ad_ads,
            'exchange_num' => $marketContract->exchange_num,
            'date' => $marketContract->date,
        ];
    }

}