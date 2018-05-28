<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use Dingo\Api\Http\FormRequest;

class AdLaunchRequest extends FormRequest
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
                    'aid' => 'required|integer',
                    'atid' => 'required|integer',
                    'atiid' => 'required|integer',
                    'areaid' => 'required|integer',
                    'marketid' => 'required|integer',
                ];
                break;
            case 'PATCH':
                return [
                    'aoids' => 'required|array|max:10',
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
