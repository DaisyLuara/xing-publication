<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

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
                    'oids' => 'required|array|max:10',
                    'default_plid' => 'required',
                ];
                break;
            case 'PATCH':
                return [
                    'tvoids' => 'required|array|max:10',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'default_plid' => 'default_plid(节目)',
            'sdate' => 'sdate(开始时间)',
            'edate' => 'edate(结束时间)',
            'tvoids' => 'tvoids(节目投放ID)',
        ];
    }
}