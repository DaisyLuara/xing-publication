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
                    'team_project_id' => 'required|integer|exists:team_projects,id',
                    'bug_num' => 'required|integer|min:1',
                    'description' => 'string',
                ];
                break;
            case 'PATCH':
                return [
                    'team_project_id' => 'required|integer|exists:team_projects,id',
                    'bug_num' => 'required|integer|min:0',
                    'description' => 'string',
                ];
                break;
            default:
                return [];
        }

    }
}