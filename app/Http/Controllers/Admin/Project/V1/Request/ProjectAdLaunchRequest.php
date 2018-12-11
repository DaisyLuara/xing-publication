<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

use App\Http\Requests\Request;

class ProjectAdLaunchRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'Post':
                return [
                    'piid' => 'required|integer',
                    'oid' => 'required|array|max:10',
                    'visiable' => 'required|integer|in:0,1',
                    'type' => 'required|string',
                    'wiid' => 'required|integer',
                    'url' => 'required|string',
                    'title' => 'required|string',
                    'image' => 'required|string',
                    'info' => 'required|string',
                    'reply' => 'required|string',
                    'only' => 'required|integer|in:0,1'
                ];
                break;
            case 'PATCH':
                return [
                    'adids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}