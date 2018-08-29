<?php

namespace App\Http\Controllers\Admin\WordFilter\V1\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WordFilterController extends Controller
{
    public function filter(Request $request)
    {
        $dict = new \SimpleDict(app_path("/Http/Controllers/Admin/WordFilter/V1/Lexicon/Lexicon.txt"));

        // 简单替换
        $replaced = $dict->replace($request->str, " ❤️❤️ ");
        return $replaced;
    }
}
