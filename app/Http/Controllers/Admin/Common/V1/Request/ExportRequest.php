<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ExportRequest extends Request
{
    public function rules()
    {
        return [
            'type' => ['required', Rule::in(['marketing', 'point', 'project', 'daily_average', 'project_point', 'marketing_top', 'old_marketing','person_reward','coupon','team_project'])]
        ];
    }
}
