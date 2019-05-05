<?php

namespace App\Http\Controllers\Admin\Credit\V1\Transformer;

use App\Http\Controllers\Admin\Credit\V1\Models\CreditLog;
use League\Fractal\TransformerAbstract;

class CreditLogTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['credit_config'];

    public function transform(CreditLog $creditLog): array
    {
        return [
            'id' => $creditLog->id,
            'uid' => $creditLog->uid,
            'username' => ($creditLog->ar_member) ? $creditLog->ar_member->username : $creditLog->uid,
            'mobile' => ($creditLog->ar_member) ? $creditLog->ar_member->mobile : $creditLog->uid,
            'ccid' => $creditLog->ccid,
            'credits' => $creditLog->credits,
            'href' => $creditLog->href,
            'ps' => $creditLog->ps,
            'date' => $creditLog->date,
            'clientdate' => $creditLog->clientdate,
        ];
    }

    public function includeCreditConfig(CreditLog $creditLog)
    {
        $credit_config = $creditLog->credit_config;
        if ($credit_config) {
            return $this->item($credit_config, new CreditConfigTransformer());
        }
    }
}