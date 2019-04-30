<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/30
 * Time: 下午3:37
 */

namespace App\Http\Controllers\Admin\Media\V1\Request;


use App\Http\Controllers\Admin\Auditing\V1\Models\Activity;
use App\Http\Requests\Request;

class ActivityMediaRequest extends Request
{
    public function rules()
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
        return [];
    }
}