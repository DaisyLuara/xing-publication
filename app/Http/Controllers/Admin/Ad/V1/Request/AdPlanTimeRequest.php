<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AdPlanTimeRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'aid' =>  'required|integer|exists:ar.avr_ad_info,aid',
                    'mode' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'ori' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'screen' => ['integer','required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'cdshow' => ['required', Rule::in([0, 1])],
                    'ktime' => 'required|integer|min:0|max:9999',
                    'only' =>['required', Rule::in([0, 1])],
                    'visiable' => ['required', Rule::in([0, 1])],
                    'shm' => 'required|string',
                    'ehm' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'mode' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'ori' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'screen' => ['integer','required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'cdshow' => ['required', Rule::in([0, 1])],
                    'ktime' => 'required|integer|min:0|max:9999',
                    'only' =>['required', Rule::in([0, 1])],
                    'visiable' => ['required', Rule::in([0, 1])],
                    'shm' => 'required|string',
                    'ehm' => 'required|string',
                ];
                break;
            default:
                return [];
        }
    }
}