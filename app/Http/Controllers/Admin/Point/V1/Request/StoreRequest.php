<?php

namespace App\Http\Controllers\Admin\Point\V1\Request;

use App\Http\Requests\Request;

class StoreRequest extends Request
{
    public function rules()
    {
        return [
            'type' => 'required',
            'company_id' => 'required',
            'areaid' => 'required',
            'name' => 'required|string',
        ];
    }

}
