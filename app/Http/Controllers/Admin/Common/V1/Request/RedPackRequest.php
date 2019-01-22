<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class RedPackRequest extends Request
{
    public function rules()
    {
        return [
            'sign' => 'required|string'
        ];
    }
}
