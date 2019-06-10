<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Request;

use App\Http\Requests\Request;

class ShortUrlRequest extends Request
{
    public function rules(): array
    {
        $method = $this->method();
        if ($method === 'POST') {
            return [
                'target_url' => 'required|url',
                'url_type' => 'required|in:0,1'
            ];
        }
        return [];
    }


}
