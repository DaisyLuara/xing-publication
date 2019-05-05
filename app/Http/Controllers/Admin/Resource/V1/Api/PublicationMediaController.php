<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/5
 * Time: 下午5:05
 */

namespace App\Http\Controllers\Admin\Resource\V1\Api;


use App\Http\Controllers\Admin\Resource\V1\Models\PublicationMedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicationMediaController extends Controller
{
    public function index(Request $request,PublicationMedia $media){
        $query=$media->query();
        $media=$query->paginate(10);
    }
}