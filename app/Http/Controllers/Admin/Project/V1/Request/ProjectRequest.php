<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

use Dingo\Api\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'Post':
                return [
                    'cid' => 'required|integer',
                    'pid' => 'required|integer',
                    'tid' => 'required|integer',
                    'name' => 'required|string',
                    'info' => 'required|string',
                    'icon' => 'required|string',
                    'image' => 'required|string',
                    'link' => 'required|string',
                    'size' => 'required|integer',
                    'packname' => 'required|string',
                    'versioncode' => 'required|string',
                    'versionname' => 'required|string',
                    'open' => 'required|integer|in:0,1',
                    'scan' => 'required|integer|in:0,1',
                    'linkall' => 'required|integer|in:0,1',
                ];
                break;
            case 'PATCH':
                return [
                    'ids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}