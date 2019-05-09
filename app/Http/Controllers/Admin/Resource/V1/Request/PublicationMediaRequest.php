<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/6
 * Time: 上午9:45
 */

namespace App\Http\Controllers\Admin\Resource\V1\Request;


use App\Http\Requests\Request;

class PublicationMediaRequest extends Request
{
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'name' => 'required|string',
                'key' => 'required|string',
                'size' => 'required|integer'
            ];
        }
        if ($method === 'PATCH') {
            return [
                'name' => 'filled'
            ];
        }
        return [];
    }
}