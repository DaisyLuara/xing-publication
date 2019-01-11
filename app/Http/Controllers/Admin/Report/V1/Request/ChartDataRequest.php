<?php

namespace App\Http\Controllers\Admin\Report\V1\Request;

use App\Http\Requests\Request;

class ChartDataRequest extends Request
{
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
        ];
    }
}
