<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class UserCouponRequest extends Request
{

    public function rules()
    {
        return [
            'sign' => 'required',
            'coupon_batch_id' => 'required',
            'start_date' => 'filled|date_format:Y-m-d H:i:s',
            'end_date' => 'filled|date_format:Y-m-d H:i:s',
        ];
    }
}
