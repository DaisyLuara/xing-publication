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
                'target_url' => ['required', 'url', function ($key, $value, $fail) {
                    $urlType = $this->input('url_type');
                    $contain = strpos($value, env('COOKIE_DOMAIN'));
                    if (((int)$urlType === 1 && $contain) || ((int)$urlType === 0 && !$contain)) {
                        $fail('请检查链接和链接类型');
                    }
                }],
                'url_type' => 'required|in:0,1'
            ];
        }
        return [];
    }

}
