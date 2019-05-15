<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AdPlanRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'aids' => 'required|array|max:10',//素材ID
                    'info' => 'string',
                    'atid' => 'required|integer|exists:ar.avr_ad_trade,atid',
                    'name' => 'required|string',
                    'icon' => 'url',
                    'type' => ['required', Rule::in([AdPlan::TYPE_BID_SCREEN, AdPlan::TYPE_SMALL_SCREEN])],
                    'mode' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'ori' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'screen' => ['integer','required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'cdshow' => ['required', Rule::in([0, 1])],
                    'ktime' => 'required|integer|min:1',
                    'shm' => 'required|string',
                    'ehm' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'aids' => 'required|array|max:10',//素材ID
                    'info' => 'string',
                    'atid' => 'required|integer|exists:ar.avr_ad_trade,atid',
                    'name' => 'required|string',
                    'icon' => 'url',
                    'type' => ['required', Rule::in([AdPlan::TYPE_BID_SCREEN, AdPlan::TYPE_SMALL_SCREEN])],
                    'mode' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'ori' => ['required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'screen' => ['integer','required_if:type,' . AdPlan::TYPE_BID_SCREEN],
                    'cdshow' => ['required', Rule::in([0, 1])],
                    'ktime' => 'required|integer|min:1',
                    'shm' => 'required|string',
                    'ehm' => 'required|string',
                ];
                break;
            case 'PUT':
                return [
                    'info' => 'string',
                    'atid' => 'required|integer|exists:ar.avr_ad_trade,atid',
                    'name' => 'required|string',
                    'icon' => 'url',
                ];
                break;
            default:
                return [];
        }
    }
}