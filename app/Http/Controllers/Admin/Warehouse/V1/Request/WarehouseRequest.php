<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Request;

use App\Http\Requests\Request;

class WarehouseRequest extends Request
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
                    'name' => 'required|max:50',
                    'address' => 'required|max:50'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'required|max:50',
                    'address' => 'required|max:50'
                ];
                break;
            default:
                return [];
        }
    }

}
