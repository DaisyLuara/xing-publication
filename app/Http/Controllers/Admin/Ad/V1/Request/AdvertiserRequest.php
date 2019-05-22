<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use App\Http\Requests\Request;

class AdvertiserRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'atid' => 'required|integer',
                    'name' => 'required|string',
                    'icon' => 'required|string',
                    'info' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'atiids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}