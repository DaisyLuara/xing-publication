<?php

namespace App\Http\Requests\Api\V1;

use Dingo\Api\Http\FormRequest;

class ProjectLaunchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'oid' => 'required|array',
                    'default_plid' => 'required',
                    'sdate' => 'required',
                    'edate' => 'required',
                ];
                break;
            case 'PATCH':
                return [
                    'tvoids' => 'required',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'default_plid' => '节目',
            'sdate' => '开始时间',
            'edate' => '结束时间',
            'tvoids' => '节目投放ID',
        ];
    }
}