<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Request;

use App\Http\Requests\Request;

class CouponPackRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sign' => 'required|string',
            'belong' => 'required|string',
            'qiniu_id' => 'required|integer',
        ];
    }
}
