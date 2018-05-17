<?php

namespace App\Http\Requests\Api\V1;


use Dingo\Api\Http\FormRequest;

class AdTradeRequest extends FormRequest
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
                    'name' => 'required|string',
                    'icon' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'atids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}