<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/6/20
 * Time: 14:26
 */

namespace App\Http\Controllers\Admin\Coupon\V1\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyBatchesRequest extends FormRequest
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
        return [
            'gender' => ['required_without_all:rate,min_age,max_age', Rule::in(['female', 'male', 'none'])],
            'rate' => ['digits_between:1,3', 'required_without_all:gender,min_age,max_age'],
            'min_age' => ['digits_between:1,2', 'required_without_all:rate,gender,max_age', 'required_with:max_age'],
            'max_age' => ['digits_between:1,2', 'required_without_all:rate,gender,min_age', 'required_with:min_age'],
        ];
    }
}
