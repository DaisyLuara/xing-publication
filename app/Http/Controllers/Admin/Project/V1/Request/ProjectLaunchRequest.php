<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

use App\Http\Requests\Request;

class ProjectLaunchRequest extends Request
{

    public function rules(): ?array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'oids' => 'required|array|max:10',
                    'default_plid' => 'required',
                    'default_bid' => 'required'
                ];
                break;
            case 'PATCH':
                return [
                    'tvoids' => 'required|array|max:10',
                ];
                break;
        }
    }

    public function attributes(): array
    {
        return [
            'default_plid' => '节目',
            'default_bid' => '皮肤',
            'sdate' => '开始时间',
            'edate' => '结束时间',
            'tvoids' => '节目投放ID',
        ];
    }
}