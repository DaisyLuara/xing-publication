<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/2/18
 * Time: 上午11:23
 */

namespace App\Http\Controllers\Admin\Warehouse\V1\Request;


use App\Http\Requests\Request;

class AttributeValueRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'attribute_id' => 'required',
                    'value' => 'required'
                ];
                break;
            default:
                return [];
        }
    }
}