<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/7
 * Time: 下午3:50
 */

namespace App\Http\Controllers\Admin\Resource\V1\Request;


use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PublicationMediaGroupRequest extends Request
{
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'name' => 'required|unique:publication_media_groups'
            ];
        }
        if ($method === 'PATCH') {
            return [
                'name' => ['filled', Rule::unique('publication_media_groups')->ignore($this->route('group')->id)]
            ];
        }
        return [];
    }
}