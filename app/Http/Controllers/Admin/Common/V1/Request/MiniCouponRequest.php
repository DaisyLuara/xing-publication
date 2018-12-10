<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class MiniCouponRequest extends Request
{
    public function rules()
    {
        return [
            'z' => 'required|string'
        ];
    }
}
