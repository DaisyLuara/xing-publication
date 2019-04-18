<?php

namespace App\Http\Controllers\Admin\Feedback\V1\Request;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FeedbackRequest extends Request
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
                    'content' => 'required|string',
                    'photo_media_ids' => "nullable|array",
                    'video_media_id' => ['nullable', 'integer', Rule::exists('media', 'id')],
                    'parent_id' =>  ['required', 'integer', Rule::exists('feedback', 'id')],
                ];
                break;
            case 'PUT':
                return [
                ];
                break;
        }
    }
}
