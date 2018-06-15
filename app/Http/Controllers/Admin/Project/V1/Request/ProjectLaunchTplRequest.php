<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

use Dingo\Api\Http\FormRequest;

class ProjectLaunchTplRequest extends FormRequest
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
                    'name' => 'required'
                ];
                break;
            case 'PATCH':
                return [
                ];
                break;
        }
    }
}