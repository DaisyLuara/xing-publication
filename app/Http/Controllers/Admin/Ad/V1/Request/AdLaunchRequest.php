<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AdLaunchRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'marketid' => 'required|integer|exists:ar.avr_official_market,marketid',
                    'oids' => 'required|array|max:10',
                    'piid' => 'integer|exists:ar.ar_product_list,id',
                    'atiid' => 'required|integer|exists:ar.avr_ad_trade_info,atiid',
                    'sdate' => 'date',
                    'edate' => 'date',
                    'visiable' => ['required', Rule::in([0, 1])],
                    'only' => ['required', Rule::in([0, 1])],
                ];
                break;
            case 'PATCH':
                return [
                    'aoids' => 'required|array|max:10',
                    'keys' => 'required|array|max:10',
                    'atiid' => 'integer|exists:ar.avr_ad_trade_info,atiid',
                    'sdate' => 'date',
                    'edate' => 'date',
                    'visiable' => [Rule::in([0, 1])],
                    'only' => [Rule::in([0, 1])],
                ];
                break;
            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            'atid' => 'atid(行业ID)',
            'atiid' => 'atiid(广告主ID)',
            'aid' => 'aid(广告ID)',
            'areaid' => 'areaid(区域ID)',
            'marketid' => 'marketid(商场ID)',
            'oid' => 'oid(点位ID)',
        ];
    }
}
