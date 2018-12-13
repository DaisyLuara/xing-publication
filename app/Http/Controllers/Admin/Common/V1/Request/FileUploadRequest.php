<?php

namespace App\Http\Controllers\Admin\Common\V1\Request;

use App\Http\Requests\Request;

class FileUploadRequest extends Request
{
    public function rules()
    {
        return [
            'scene' => 'required|string',
        ];
    }
}
