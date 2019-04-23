<?php

namespace App\Http\Controllers\Admin\Coupon\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ImportCouponRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->method() === 'PUT') {
            return [
                'marketid' => 'integer|nullable',
                'oid' => 'array|max:10',
                'scene_type' => Rule::in([1, 2, 3, 4]),
                'media_id' => 'required|integer|exists:media,id',
                'policy_id' => 'required|integer|exists:policies,id',
            ];
        }
        return [];

    }
}