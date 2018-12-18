<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class CouponBatchRequest extends Request
{
    public function rules()
    {
        return [
            'policy_id' => 'required'
        ];
    }
}
