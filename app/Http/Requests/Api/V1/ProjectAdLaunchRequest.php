<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/16
 * Time: 16:09
 */

namespace App\Http\Requests\Api\V1;


use Dingo\Api\Http\FormRequest;

class ProjectAdLaunchRequest extends FormRequest
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
                    'piid' => 'required|integer',
                    'oid' => 'required|array|max:10',
                    'visiable' => 'required|integer|in:0,1',
                    'type' => 'required|string',
                    'wiid' => 'required|integer',
                    'url' => 'required|string',
                    'title' => 'required|string',
                    'image' => 'required|string',
                    'info' => 'required|string',
                    'reply' => 'required|string',
                    'only' => 'required|integer|in:0,1'
                ];
                break;
            case 'PATCH':
                return [
                    'adids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}