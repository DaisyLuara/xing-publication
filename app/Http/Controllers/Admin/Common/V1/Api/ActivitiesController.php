<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Transformer\ActivityTransformer;
use App\Http\Controllers\Admin\Activity\V1\Models\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()
    {
        $activities = Activity::query()->paginate(20);

        return $this->response->paginator($activities, new ActivityTransformer());
    }

}
