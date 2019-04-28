<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/28
 * Time: 上午10:20
 */

namespace App\Http\Controllers\Admin\Auditing\V1\Request;

use App\Http\Requests\Request;

class ActivityMediaRequest extends Request
{
    public function rules(): ?array
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