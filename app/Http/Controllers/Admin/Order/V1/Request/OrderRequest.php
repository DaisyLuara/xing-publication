<?php

namespace App\Http\Controllers\Admin\Order\V1\Request;

use App\Http\Requests\Request;
use App\Http\Controllers\Admin\Product\V1\Models\ProductSku;
use Illuminate\Validation\Rule;

class OrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        switch ($this->method()) {
            case 'POST':
                return [];
            case 'PATCH':
                return [];
            default:
                return [];
        }


    }

    public function attributes(): array
    {
        return [
            'amount' => '商品数量'
        ];
    }

    public function messages(): array
    {
        return [
            'sku_id.required' => '请选择商品'
        ];
    }

}
