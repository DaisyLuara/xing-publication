<?php

namespace App\Http\Controllers\Admin\Point\V1\Request;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'type' => 'required',
                    'company_id' => 'required',
                    'areaid' => 'required',
                    'name' => 'required|string',
                ];
            default:
                return [];
        }
    }

}
