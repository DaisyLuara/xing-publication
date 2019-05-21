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
                    'name' => 'required|string',
                    'img' => 'required|string|url',
                    'type' => 'required|string|in:static,gif,fps,video',
                    'link' => 'required|string|url',
                    'size' => 'integer',
                    'fps' => 'integer',
                    'isad' => 'required|integer|in:0,1',
                    'pass' => 'integer'
                ];
                break;
            case 'PATCH':
                return [
                    'atid' => 'required|integer',
                    'name' => 'required|string',
                    'img' => 'required|string|url',
                    'type' => 'required|string|in:static,gif,fps,video',
                    'link' => 'required|string|url',
                    'size' => 'integer',
                    'fps' => 'integer',
                    'isad' => 'required|integer|in:0,1',
                    'pass' => 'integer'
                ];
                break;
            default:
                return [];
        }
    }
}