<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/16
 * Time: 16:35
 */

namespace App\Http\Requests\Api\V1;


use Dingo\Api\Http\FormRequest;

class ProjectRequest extends FormRequest
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
                    'cid' => 'required|integer',
                    'pid' => 'required|integer',
                    'tid' => 'required|integer',
                    'name' => 'required|string',
                    'info' => 'required|string',
                    'icon' => 'required|string',
                    'image' => 'required|string',
                    'link' => 'required|string',
                    'size' => 'required|integer',
                    'packname' => 'required|string',
                    'versioncode' => 'required|string',
                    'versionname' => 'required|string',
                    'open' => 'required|integer',
                    'scan' => 'required|integer',
                    'linkall' => 'required|integer',
                ];
                break;
            case 'PATCH':
                return [
                    'ids' => 'required|array|max:10'
                ];
                break;
            default:
                return [];
        }
    }
}