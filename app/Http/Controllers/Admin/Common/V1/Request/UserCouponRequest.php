<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use Dingo\Api\Http\FormRequest;

class UserCouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ['sign' => 'required', 'coupon_batch_id' => 'required'];
    }
}
