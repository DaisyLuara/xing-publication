<?php

namespace App\Http\Controllers\Admin\Point\V1\Request;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Requests\Request;

class PointRequest extends Request
{
    public function rules(): ?array
    {
        switch ($this->method()) {
            case 'GET':
                return [
                    'lat' => 'required',
                    'lng' => 'required',
                ];
            case 'POST':
                return [
                    'areaid' => 'required',
                    'marketid' => ['required', function ($key, $value, $fail) {
                        $market = Market::query()->where('marketid', $value)->first();
                        if ($market->areaid !== $this->input('areaid')) {
                            $fail('区域商场不匹配');
                        }
                    }],
                    'name' => 'required',
                    'site_z' => 'required',
                    'contract.type' => 'required',
                    'contract.pay' => 'required',
                    'contract.enter_sdate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.enter_edate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.oper_sdate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.oper_edate' => 'required|date_format:Y-m-d H:i:s',
                    'contract.mode' => 'required',
                    'share.offer' => 'required',
                    'share.mad' => 'required',
                    'share.play' => 'required',
                    'share.qrcode' => 'required',
                    'share.wx_pa' => 'required',
                    'share.wx_mp' => 'required',
                    'share.app' => 'required',
                    'share.phone' => 'required',
                    'share.coupon' => 'required',
                ];
            case 'PATCH':
                return [
                    'areaid' => 'filled',
                    'marketid' => 'filled',
                    'name' => 'filled',
                    'site_z' => 'filled',
                    'contract.type' => 'filled',
                    'contract.pay' => 'filled',
                    'contract.enter_sdate' => 'filled|date_format:Y-m-d H:i:s',
                    'contract.enter_edate' => 'filled|date_format:Y-m-d H:i:s',
                    'contract.oper_sdate' => 'filled|date_format:Y-m-d H:i:s',
                    'contract.oper_edate' => 'filled|date_format:Y-m-d H:i:s',
                    'contract.mode' => 'filled',
                    'share.offer' => 'filled',
                    'share.mad' => 'filled',
                    'share.play' => 'filled',
                    'share.qrcode' => 'filled',
                    'share.wx_pa' => 'filled',
                    'share.wx_mp' => 'filled',
                    'share.app' => 'filled',
                    'share.phone' => 'filled',
                    'share.coupon' => 'filled',
                ];
            default:
                return [];
        }
    }

    public function attributes(): array
    {
        return [
            'areaid' => '区域',
            'marketid' => '场地',
            'name' => '点位名称',
            'bd_z' => 'bd标识',
            'site_z' => '场地主标识'
        ];
    }

}
