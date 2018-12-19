<?php

namespace App\Http\Controllers\Admin\Media\V1\Request;

use App\Http\Requests\Request;

class MediaRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        switch ($this->method()) {
            case "GET":
                return [
                    'type' => 'required|string|in:image,video,file',
                ];
            case 'POST':
                $rules = [
                    'type' => 'required|string|in:image,video,file',
                ];

                if ($this->type == 'image') {
                    $rules['file'] = 'filled|mimes:jpeg,jpg,png,gif|max:10240';
                } else if ($this->type == 'video') {
                    $rules['file'] = 'filled|mimes:mp4|max:51200';
                } else {
                    $rules['file'] = 'filled';
                }

                return $rules;
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
