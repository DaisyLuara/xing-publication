<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Request;

use App\Http\Requests\Request;

class ProductRequest extends Request
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
                    'sku' => 'required',
                    'supplier' => 'required|max:50'
                ];
                break;
            case 'PATCH':
                return [
                    'sku' => 'required|max:50',
                    'supplier' => 'required|max:50'
                ];
                break;
            default:
                return [];
        }

    }
}
