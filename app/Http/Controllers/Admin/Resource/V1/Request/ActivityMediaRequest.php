<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/28
 * Time: 上午10:20
 */

namespace App\Http\Controllers\Admin\Resource\V1\Request;

use App\Http\Controllers\Admin\Resource\V1\Models\Activity;
use App\Http\Requests\Request;

class ActivityMediaRequest extends Request
{
    public function rules(): ?array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'name' => 'required|string',
                'key' => 'required|string',
                'size' => 'required|integer',
                'activity_id' => ['required', static function ($key, $value, $fail) {
                    $activity = Activity::find($value);
                    if (!$activity) {
                        $fail('活动不存在');
                        return;
                    }
                }]
            ];
        }
        if ($method === 'PATCH') {
            return [
                'status' => 'required|in:0,1'
            ];
        }
        return [];
    }
}