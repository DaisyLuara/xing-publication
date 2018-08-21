<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/8/21
 * Time: 11:07
 */

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WordFilterController extends Controller
{
    public function test(Request $request)
    {
//        $file = fopen(app_path("/Http/Controllers/Admin/Face/V1/Lexicon/CensorWords.txt"), 'r');
//        $newFile = fopen(app_path("/Http/Controllers/Admin/Face/V1/Lexicon/test.txt"), 'w+');
//        while (!feof($file)) {
//            fwrite($newFile, rtrim(fgets($file)) . "\t1\n");
//        }
//        \SimpleDict::make(app_path("/Http/Controllers/Admin/Face/V1/Lexicon/test.txt"), app_path("/Http/Controllers/Admin/Face/V1/Lexicon/Lexicon.txt"));
        $dict = new \SimpleDict(app_path("/Http/Controllers/Admin/Face/V1/Lexicon/Lexicon.txt"));

        // 简单替换
        $replaced = $dict->replace($request->str, "**");
        dd($replaced);
        // 高级替换
//        $replaced = $dict->replace("some text here...", function ($word, $value) {
//            return "[$word -> $value]";
//        });
    }
}