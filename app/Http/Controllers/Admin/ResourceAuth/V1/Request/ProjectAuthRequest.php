<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Request;

use App\Http\Requests\Request;

class ProjectAuthRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'customer_id' => 'required|integer|exists:customers,id',
                    'project_id' => 'required|integer|exists:ar.ar_product_list,id',
                    'skin_id' => 'required|integer|exists:ar.avr_goods_bag,bid',
                ];
                break;

            case 'PATCH':
                return [
                    'customer_id' => 'required|integer|exists:customers,id',
                    'project_id' => 'required|integer|exists:ar.ar_product_list,id',
                    'skin_id' => 'required|integer|exists:ar.avr_goods_bag,bid',
                ];
                break;

            default:
                return [];
        }
    }

    public function attributes(): array
    {
        return [
            'customer_id' => 'customer_id(场地主)',
            'project_id' => 'project_id(节目)',
            'skin_id' => 'skin_id(皮肤)',
        ];
    }
}
