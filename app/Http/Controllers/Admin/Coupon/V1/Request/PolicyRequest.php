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

class PolicyRequest extends Request
{

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
                    'name' => 'required',
                    'type' => Rule::in([1, 2]),
                    'per_person_unlimit' => Rule::in([0, 1]),
                    'per_person_times' => 'required|integer',
                    'per_person_per_day_unlimit' => Rule::in([0, 1]),
                    'per_person_per_day_times' => 'required|integer',
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'required',
                    'type' => Rule::in([1, 2]),
                    'per_person_unlimit' => Rule::in([0, 1]),
                    'per_person_times' => 'required|integer',
                    'per_person_per_day_unlimit' => Rule::in([0, 1]),
                    'per_person_per_day_times' => 'required|integer',
                ];
                break;
        }
    }
}
