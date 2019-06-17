<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ExportRequest extends Request
{
    public function rules(): array
    {
        $type = [
            'marketing', 'point', 'project',
            'daily_average', 'project_point', 'marketing_top',
            'person_reward', 'coupon', 'team_project',
            'play_times', 'contract_revenue', 'short_url'
        ];
        return [
            'type' => ['required', Rule::in($type)]
        ];
    }
}
