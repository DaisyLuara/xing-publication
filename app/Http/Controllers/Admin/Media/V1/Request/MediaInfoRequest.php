<?php

namespace App\Http\Controllers\Admin\Media\V1\Request;

use App\Http\Requests\Request;

class MediaInfoRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        switch ($this->method()) {
            case 'POST':
                return [
                    'media_id' => 'required|integer|exists:media,id',
                    'name' => 'required',
                ];
            case 'PATCH':
                return [
                    'name' => 'filled'
                ];
            case 'DELETE':
                return [
                    'ids' => 'required|array|max:10'
                ];
        }
    }
}
