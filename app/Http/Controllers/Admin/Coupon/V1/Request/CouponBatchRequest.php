<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Request;

use Illuminate\Validation\Rule;
use Dingo\Api\Http\FormRequest;

class CouponBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string',
                    'description' => 'string',
                    'image_url' => 'url',
                    'start_date' => 'date',
                    'end_date' => 'date|after_or_equal:start_date',
                    'amount' => 'alpha_num',
                    'count' => 'alpha_num',
                    'stock' => 'alpha_num',
                    'people_max_get' => 'alpha_num',
                    'pmg_status' => Rule::in([0, 1]),
                    'day_max_get' => 'alpha_num',
                    'dmg_status' => Rule::in([0, 1]),
                    'is_fixed_date' => Rule::in([0, 1]),
                    'delay_effective_day' => 'alpha_num',
                    'effective_day' => 'alpha_num',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'string',
                    'description' => 'string',
                    'image_url' => 'url',
                    'start_date' => 'date',
                    'end_date' => 'date|after_or_equal:start_date',
                    'amount' => 'alpha_num',
                    'count' => 'alpha_num',
                    'stock' => 'alpha_num',
                    'people_max_get' => 'alpha_num',
                    'pmg_status' => Rule::in([0, 1]),
                    'day_max_get' => 'alpha_num',
                    'dmg_status' => Rule::in([0, 1]),
                    'is_fixed_date' => Rule::in([0, 1]),
                    'delay_effective_day' => 'alpha_num',
                    'effective_day' => 'alpha_num',
                ];

                break;
        }

    }
}
