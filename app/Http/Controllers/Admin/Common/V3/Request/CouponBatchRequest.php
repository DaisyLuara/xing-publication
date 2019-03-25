<?php

namespace App\Http\Controllers\Admin\Common\V3\Request;

use App\Http\Requests\Request;

class CouponBatchRequest extends Request
{

    public function rules()
    {
        return [
            'belong' => 'required|string',
            'oid' => 'required|integer',
        ];
    }
}
