<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class TaobaoCouponRequest extends Request
{

    public function rules()
    {
        return [
            'openuid' => 'required|string'
        ];
    }
}
