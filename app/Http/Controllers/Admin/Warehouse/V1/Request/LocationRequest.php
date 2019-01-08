<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LocationRequest extends Request
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
                    'warehouse_id' => 'required|max:50'
                ];
                break;
            case 'PATCH':
                return [
                    'name' => 'required|max:50'
                ];
                break;
            default:
                return [];
        }

    }
}
