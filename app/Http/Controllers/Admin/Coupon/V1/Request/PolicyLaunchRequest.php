<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Request;

use App\Http\Requests\Request;

class PolicyLaunchRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'policy_id' => 'required|numeric',
                    'project_id' => 'required|numeric',
                    'oid' => 'required|numeric',
                    'versionname' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'policy_id' => 'required|numeric',
                    'project_id' => 'required|numeric',
                    'oid' => 'required|numeric',
                    'versionname' => 'required|string',
                ];
                break;
            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            'policy_id' => 'policy_id(节目)',
            'project_id' => 'project_id(节目)',
            'oid' => 'oid(点位ID)',
        ];
    }
}
