<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class ShortUrlRequest extends Request
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'url' => 'required|url',
                ];
                break;
            default:
                return [];
        }
    }


}
