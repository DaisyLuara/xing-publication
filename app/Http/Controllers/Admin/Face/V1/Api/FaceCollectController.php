<?php

namespace App\Http\Controllers\Admin\Face\V1\Api;

use App\Http\Controllers\Admin\Face\V1\Transformer\FaceCollectTransformer;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCollect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaceCollectController extends Controller
{
    public function index(Request $request, FaceCollect $faceCollect)
    {
        $query = $faceCollect->query();
        $collects = $query->paginate(10);
        return $this->response->paginator($collects, new FaceCollectTransformer());
    }
}
