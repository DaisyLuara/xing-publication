<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AdPlanRequest extends Request
{

    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'info' => 'string',
                    'atid' => 'required|integer|exists:ar.avr_ad_trade,atid',
                    'name' => 'required|string',
                    'icon' => 'url',
                    'type' => ['required', Rule::in([AdPlan::TYPE_BID_SCREEN, AdPlan::TYPE_SMALL_SCREEN])],
                    'hardware' => ['required', Rule::in([0, 1])],
                    'tmode' => ['required', Rule::in(['div', 'hours'])],
                ];
                break;
            case 'PATCH':
                return [
                    'info' => 'string',
                    'atid' => 'required|integer|exists:ar.avr_ad_trade,atid',
                    'name' => 'required|string',
                    'icon' => 'url',
                    'hardware' => ['required', Rule::in([0, 1])],
                    'tmode' => ['required', Rule::in(['div', 'hours'])],
                ];
                break;
            default:
                return [];
        }
    }
}