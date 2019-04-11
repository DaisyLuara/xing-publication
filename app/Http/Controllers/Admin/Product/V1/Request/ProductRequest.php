<?php

namespace App\Http\Controllers\Admin\Product\V1\Request;

use App\Http\Requests\Request;

class ProductRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required',
                    'image' => 'required|url',
                    'description' => 'required',
                ];
            default:
                return [];
        }
    }

}
