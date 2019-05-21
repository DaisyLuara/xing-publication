<?php

namespace App\Http\Controllers\Admin\Project\V1\Request;

use App\Http\Requests\Request;

class ProjectLaunchTplScheduleRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'bid' => 'required',
                    'project_id' => 'required',
                    'date_start' => 'required|date_format:H:i',
                    'date_end' => 'required|date_format:H:i',
                ];
                break;
            case 'PATCH':
                return [
                    'bid' => 'filled',
                    'project_id' => 'filled',
                    'date_start' => 'date_format:H:i',
                    'date_end' => 'date_format:H:i',
                ];
                break;
        }
    }
}