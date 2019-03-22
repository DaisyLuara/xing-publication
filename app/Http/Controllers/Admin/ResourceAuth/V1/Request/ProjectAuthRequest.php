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
    public function rules()
    {
        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'customer_id' => 'required|integer|exists:customers,id',
                    'project_id' => 'required|integer|exists:ar.ar_product_list,id',
                ];
                break;

            case 'PATCH':
                return [
                    'customer_id' => 'required|integer|exists:customers,id',
                    'project_id' => 'required|integer|exists:ar.ar_product_list,id',
                ];
                break;

            default:
                return [];
        }
    }

    public function attributes()
    {
        return [
            'customer_id' => 'customer_id(场地主)',
            'project_id' => 'project_id(节目)',
        ];
    }
}
