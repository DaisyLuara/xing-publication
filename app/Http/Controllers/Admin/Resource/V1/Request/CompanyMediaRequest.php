<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/28
 * Time: 上午11:18
 */

namespace App\Http\Controllers\Admin\Resource\V1\Request;


use App\Http\Requests\Request;

class CompanyMediaRequest extends Request
{
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'status' => 'required|in:0,1'
            ];
        }
        return [];
    }
}