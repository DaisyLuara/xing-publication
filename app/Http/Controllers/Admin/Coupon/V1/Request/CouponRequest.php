<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CouponRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['filled', Rule::in([0, 1, 2, 3])],
            'start_date' => 'filled|date_format:Y-m-d H:i:s',
            'end_date' => 'filled|date_format:Y-m-d H:i:s',
        ];
    }
}
