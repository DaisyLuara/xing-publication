<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\FaceCollect;
use App\Transformers\FaceCollectTransformer;

class FaceCollectController extends Controller
{
    public function index(Request $request, FaceCollect $faceCollect)
    {
        $query = $faceCollect->query();
        $collects = $query->paginate(10);
        return $this->response->paginator($collects, new FaceCollectTransformer());
    }
}
