<?php

namespace App\Http\Controllers\Admin\Common\V2\Request;

use App\Http\Requests\Request;

class UserCouponRequest extends Request
{

    public function rules()
    {
        return [
            'z' => 'required',
            'start_date' => 'filled|date_format:Y-m-d H:i:s',
            'end_date' => 'filled|date_format:Y-m-d H:i:s',
            'belong' => 'string',
            'coupon_batch_id' => 'integer',
        ];
    }
}
