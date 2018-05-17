<?php

namespace App\Http\Requests\Api\V1;


use Dingo\Api\Http\FormRequest;

class AdvertisementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch ($this->method()) {
            case 'Post':
                return [
                    'atid' => 'required|integer',
                    'atiid' => 'required|integer',
                    'name' => 'required|string',
                    'img' => 'required|string',
                    'type' => 'required|string|in:static,gif,fps,video',
                    'link' => 'required|string',
                    'size' => 'required|integer',
                    'fps' => 'required|integer',
                    'isad' => 'required|integer|in:0,1',
                ];
                break;
            case 'PATCH':
                return [
                    'aids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}