<?php

namespace App\Http\Controllers\Admin\Team\V1\Request;


use App\Http\Requests\Request;

class TeamProjectBugRecordRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'belong' => 'required|string|exists:team_projects,belong',
                    "occur_date" => "required|date",
                    'description' => 'string',
                ];
                break;
            case 'PATCH':
                return [
                    "belong" => 'required|string|exists:team_projects,belong',
                    "occur_date" => "required|date",
                    'description' => 'string',
                ];
                break;
            default:
                return [];
        }

    }
}