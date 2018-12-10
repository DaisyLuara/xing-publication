<?php

namespace App\Http\Controllers\Admin\Ad\V1\Request;

use App\Http\Requests\Request;

class AdvertisementRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'atid' => 'required|integer',
                    'atiid' => 'required|integer',
                    'name' => 'required|string',
                    'img' => 'required|string',
                    'type' => 'required|string|in:static,gif,fps,video',
                    'link' => 'required|string',
                    'size' => 'required|integer',
                    'fps' => 'required|integer',
                    'isad' => 'required|integer|in:0,1',
                ];
                break;
            case 'PATCH':
                return [
                    'aids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}