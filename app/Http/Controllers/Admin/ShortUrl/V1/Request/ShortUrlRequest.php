<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Request;

use Dingo\Api\Http\FormRequest;

class ShortUrlRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


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
