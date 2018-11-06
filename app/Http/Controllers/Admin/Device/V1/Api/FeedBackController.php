<?php

namespace App\Http\Controllers\Admin\Device\V1\Api;

use App\Http\Controllers\Admin\Device\V1\Transformer\FeedBackTransformer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Device\V1\Models\FeedBack;
use App\Http\Controllers\Admin\Device\V1\Request\FeedBackRequest;

class FeedBackController extends Controller
{
    public function index(FeedBackRequest $request, FeedBack $feedBack)
    {
        $query = $feedBack->newQuery();
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('device_code')) {
            $query->where('device_code', $request->device_code);
        }

        if ($request->has('code')) {
            $query->where('code', $request->code);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $feedBack = $query->orderByDesc('id')->paginate(10);

        return $this->response->paginator($feedBack, new FeedBackTransformer());
    }

}
