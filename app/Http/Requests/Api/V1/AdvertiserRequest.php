<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/16
 * Time: 15:44
 */

namespace App\Http\Requests\Api\V1;


use Dingo\Api\Http\FormRequest;

class AdvertiserRequest extends FormRequest
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
                    'name' => 'required|string',
                    'icon' => 'required|string',
                    'info' => 'required|string',
                ];
                break;
            case 'PATCH':
                return [
                    'atiids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}