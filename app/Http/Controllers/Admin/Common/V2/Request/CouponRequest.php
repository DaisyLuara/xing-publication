<?php

namespace App\Http\Controllers\Admin\Common\V2\Request;

use App\Http\Requests\Request;

class CouponRequest extends Request
{

    public function rules()
    {
        return [
            'z' => 'required|string',
            'belong' => 'required|string',
            'oid' => 'required|integer',
            'qiniu_id' => 'required|integer',
        ];
    }
}
