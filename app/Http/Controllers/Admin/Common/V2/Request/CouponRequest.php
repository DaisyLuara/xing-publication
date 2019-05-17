<?php

namespace App\Http\Controllers\Admin\Common\V2\Request;

use App\Http\Requests\Request;

class CouponRequest extends Request
{

    public function rules()
    {
        return [
            'z' => 'required_without:sign|string',
            'sign' => 'required_without:z|string',
            'belong' => 'required|string',
            'oid' => 'required|integer',
            'qiniu_id' => 'required|integer',
        ];
    }
}
