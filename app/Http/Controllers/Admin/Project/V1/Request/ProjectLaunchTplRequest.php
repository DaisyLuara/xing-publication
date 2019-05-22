<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

use App\Http\Requests\Request;

class ProjectLaunchTplRequest extends Request
{
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
                    'name' => 'filled'
                ];
                break;
        }
    }
}